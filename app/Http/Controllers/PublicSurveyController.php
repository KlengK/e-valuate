<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveySession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicSurveyController extends Controller
{
    /**
     * Start a new survey session for a given survey.
     */
    public function start(Survey $survey): RedirectResponse
    {
        // vvv THIS IS THE UPDATED PART vvv
        // Only allow access to 'active' surveys with questions.
        if ($survey->status !== 'active' || $survey->questions()->count() === 0) {
            return redirect('/')->with('error', 'This survey is not currently available.');
        }
        // ^^^ END OF UPDATE ^^^

        $session = $survey->surveySessions()->create([
            'session_uuid' => Str::uuid(),
        ]);

        return redirect()->route('public.survey.question.show', [
            'session_uuid' => $session->session_uuid,
            'order' => 1,
        ]);
    }

    /**
     * Display a specific question in the survey.
     */
    public function showQuestion(string $session_uuid, int $order): View
    {
        $session = SurveySession::where('session_uuid', $session_uuid)->firstOrFail();
        $survey = $session->survey;
        $question = $survey->questions()->where('order', $order)->firstOrFail();

        return view('public.survey.question', [
            'survey' => $survey,
            'question' => $question,
            'session_uuid' => $session_uuid,
            'totalQuestions' => $survey->questions()->count()
        ]);
    }

    /**
     * Store the response and redirect to the next question or completion page.
     */
    public function storeResponse(Request $request, string $session_uuid, int $order): RedirectResponse
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_value' => 'required|string',
        ]);

        $session = SurveySession::where('session_uuid', $session_uuid)->firstOrFail();

        // Save the response to the database
        $session->responses()->create($validated);

        // Determine if there is a next question
        $nextQuestionOrder = $order + 1;
        $nextQuestionExists = $session->survey->questions()->where('order', $nextQuestionOrder)->exists();

        if ($nextQuestionExists) {
            // If yes, redirect to the next question
            return redirect()->route('public.survey.question.show', [
                'session_uuid' => $session_uuid,
                'order' => $nextQuestionOrder,
            ]);
        } else {
            // If no, mark the session as complete and redirect to the thank you page
            $session->update(['completed_at' => now()]);
            return redirect()->route('public.survey.complete');
        }
    }

    /**
     * Display the survey completion page.
     */
    public function complete(): View
    {
        return view('public.survey.complete');
    }
}
