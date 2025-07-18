<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth; // <-- ADD THIS LINE
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

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

    public function report(Survey $survey): \Inertia\Response
    {
        // Ensure the logged-in user owns this survey
        if ($survey->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        // Eager load relationships for efficiency
        $survey->load('questions.responses', 'surveySessions');

        $totalCompletions = $survey->surveySessions()->whereNotNull('completed_at')->count();

        $reportData = $survey->questions->map(function ($question) {
            $responses = $question->responses;
            $data = [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'total_responses' => $responses->count(),
                'results' => [],
            ];

            if ($question->question_type === 'rating') {
                $ratings = $responses->pluck('answer_value')->map(fn($val) => (int)$val);
                $data['results'] = [
                    'average' => $ratings->avg() ? round($ratings->avg(), 2) : 0,
                    'counts' => $ratings->countBy(),
                ];
            } elseif ($question->question_type === 'multiple_choice') {
                $data['results'] = $responses->pluck('answer_value')->countBy();
            } elseif ($question->question_type === 'text') {
                $data['results'] = $responses->pluck('answer_value')->all();
            }

            return $data;
        });

        return \Inertia\Inertia::render('Surveys/Report', [
            'survey' => $survey,
            'totalCompletions' => $totalCompletions,
            'reportData' => $reportData,
        ]);
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
