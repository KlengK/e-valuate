<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\SurveySession;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        // vvv THIS IS THE UPDATED PART vvv
        return Inertia::render('Surveys/Index', [
            'surveys' => Auth::user()->surveys()->latest()->get(),
        ]);
        // ^^^ END OF UPDATE ^^^
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return \Inertia\Inertia::render('Surveys/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
        public function share(Survey $survey): Response
    {
        if ($survey->user_id !== Auth::id()) {
            abort(403);
        }

        $shareUrl = route('public.survey.start', $survey);
        
        // vvv THIS IS THE NEW LOGIC vvv

        // Generate a PLAIN QR code without merging
        $qrCode = QrCode::size(400)
            ->format('svg')
            ->errorCorrection('H')
            ->color(0, 119, 190)
            ->generate($shareUrl);

        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        // Get the public URL of the logo for the frontend
        $logoUrl = asset('images/logo.png');

        return Inertia::render('Surveys/Share', [
            'survey' => $survey,
            'shareUrl' => $shareUrl,
            'qrCode' => $qrCodeBase64,
            'logoUrl' => $logoUrl, // Pass the logo URL to the frontend
        ]);
        // ^^^ END OF NEW LOGIC ^^^
    }

    /**
     * Show the responses for an individual survey session.
     */
    

    public function showSession(Survey $survey, \App\Models\SurveySession $session): \Inertia\Response
    {
        // Ensure the session belongs to the correct survey and user
        if ($survey->user_id !== \Illuminate\Support\Facades\Auth::id() || $session->survey_id !== $survey->id) {
            abort(403);
        }

        // Eager load the responses and their associated questions
        $session->load('responses.question');

        return \Inertia\Inertia::render('Surveys/Session', [
            'survey' => $survey,
            'session' => $session,
        ]);
    }

    public function show(Survey $survey): \Inertia\Response
    {
        // Ensure the logged-in user owns this survey
        if ($survey->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        return \Inertia\Inertia::render('Surveys/Show', [
            'survey' => $survey->load('questions') // Eager load the questions
        ]);
    }

    public function report(Survey $survey): Response
    {
        if ($survey->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Surveys/Report', $this->getReportData($survey));
    }

    /**
     * Get a single individual response for the report page.
     */
    public function getIndividualResponse(Survey $survey, SurveySession $session): Response
    {
        if ($survey->user_id !== Auth::id() || $session->survey_id !== $survey->id) {
            abort(403);
        }
        
        // Get the base report data using our new helper method
        $data = $this->getReportData($survey);
        
        // Load the specific session and add it to the data
        $session->load('responses.question');
        $data['individualResponse'] = $session;

        return Inertia::render('Surveys/Report', $data);
    }

    // vvv THIS IS THE NEW HELPER METHOD vvv
    /**
     * Gathers and computes all data for a survey report.
     */
    private function getReportData(Survey $survey): array
    {
        $survey->load('questions');
        $completedSessions = $survey->surveySessions()->whereNotNull('completed_at')->latest()->get();

        $reportData = $survey->questions->map(function ($question) {
            $responses = $question->responses()->get();
            $data = [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'results' => [],
            ];
            if ($question->question_type === 'rating') {
                $ratings = $responses->pluck('answer_value')->map(fn($val) => (int)$val);
                $data['results'] = ['average' => $ratings->avg() ? round($ratings->avg(), 2) : 0];
            } elseif ($question->question_type === 'multiple_choice') {
                $data['results'] = $responses->pluck('answer_value')->countBy();
            } elseif ($question->question_type === 'text') {
                $data['results'] = $responses->pluck('answer_value')->all();
            }
            return $data;
        });

        return [
            'survey' => $survey,
            'reportData' => $reportData,
            'totalCompletions' => $completedSessions->count(),
            'sessions' => $completedSessions->map(fn($s) => ['id' => $s->id, 'completed_at' => $s->completed_at]),
        ];
    }

     public function updateStatus(Request $request, Survey $survey): RedirectResponse
    {
        // Ensure the logged-in user owns this survey
        if ($survey->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:draft,active,closed',
        ]);

        $survey->update($validated);

        return Redirect::back()->with('success', 'Survey status updated.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|string|in:rating,text,multiple_choice',
            'questions.*.options' => 'nullable|array|required_if:questions.*.question_type,multiple_choice',
            'questions.*.options.*' => 'string|max:255',
        ]);

        // Use a transaction to ensure all or nothing is saved
        DB::transaction(function () use ($validated, $request) {
            // 1. Create the Survey
            $survey = $request->user()->surveys()->create([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            // 2. Loop through and create each question
            foreach ($validated['questions'] as $index => $questionData) {
                $survey->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'options' => $questionData['options'] ?? null,
                    'order' => $index + 1,
                ]);
            }
        });

        return Redirect::route('surveys.index')->with('success', 'Survey and questions created successfully.');
    }
    
}
