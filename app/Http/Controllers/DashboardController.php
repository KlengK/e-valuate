<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\SurveySession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with enhanced metrics.
     */
    public function __invoke(Request $request): InertiaResponse
    {
        $user = Auth::user();
        $userSurveyIds = $user->surveys()->pluck('id');

        // 1. "At-a-Glance" Statistics (Existing)
        $stats = [
            'totalSurveys' => $user->surveys()->count(),
            'activeSurveys' => $user->surveys()->where('status', 'active')->count(),
            'responsesToday' => SurveySession::whereIn('survey_id', $userSurveyIds)->whereDate('completed_at', Carbon::today())->count(),
            'totalResponses' => SurveySession::whereIn('survey_id', $userSurveyIds)->whereNotNull('completed_at')->count(),
        ];

        // 2. "Weekly Response Trend" Chart Data
        $weeklyTrend = SurveySession::whereIn('survey_id', $userSurveyIds)
            ->whereBetween('completed_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        // Fill in missing days with 0 counts
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $trendData[Carbon::parse($date)->format('D')] = $weeklyTrend[$date] ?? 0;
        }

        // 3. "Most Active Survey" Card
        $mostActiveSurvey = $user->surveys()
            ->withCount(['surveySessions' => function ($query) {
                $query->whereBetween('completed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }])
            ->orderByDesc('survey_sessions_count')
            ->first();

        // 4. "Latest Feedback" Snippet
        $latestFeedback = Response::whereHas('question', function ($query) {
                $query->where('question_type', 'text');
            })
            ->whereHas('surveySession', function ($query) use ($userSurveyIds) {
                $query->whereIn('survey_id', $userSurveyIds)->whereNotNull('completed_at');
            })
            ->with('question:id,question_text') // Eager load question text
            ->latest()
            ->limit(3)
            ->get();

        // 5. "Housekeeping" Alerts
        $housekeeping = [
            'draftSurveys' => $user->surveys()->where('status', 'draft')->count(),
            'oldActiveSurveys' => $user->surveys()->where('status', 'active')->where('created_at', '<', Carbon::now()->subDays(30))->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'activeSurveysList' => $user->surveys()->where('status', 'active')->withCount(['surveySessions' => fn($q) => $q->whereDate('completed_at', Carbon::today())])->latest()->get(),
            'recentActivityFeed' => SurveySession::whereIn('survey_id', $userSurveyIds)->whereNotNull('completed_at')->with('survey:id,title')->latest('completed_at')->limit(5)->get(),
            'weeklyTrendData' => $trendData,
            'mostActiveSurvey' => $mostActiveSurvey,
            'latestFeedback' => $latestFeedback,
            'housekeeping' => $housekeeping,
        ]);
    }
}
