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
        
        // First, check if the survey is closed.
        if ($survey->status === 'closed') {
            return redirect()->route('public.survey.closed', $survey);
        }

        // Then, check if it's active and has questions.
        if ($survey->status !== 'active' || $survey->questions()->count() === 0) {
            return redirect('/')->with('error', 'This survey is not currently available.');
        }
        

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

    public function closed(Survey $survey): View
    {
        return view('public.survey.closed', [
            'survey' => $survey,
        ]);
    }

     public function complete(): View
    {
        return view('public.survey.complete');
    }

    /**
     * Store the response and redirect to the next question or completion page.
     */
    public function storeResponse(Request $request, string $session_uuid, int $order): RedirectResponse
    {
        $session = SurveySession::where('session_uuid', $session_uuid)->firstOrFail();
        $question = $session->survey->questions()->where('order', $order)->firstOrFail();

        // vvv THIS IS THE FIX vvv
        // The validation rule for 'answer_value' is now more explicit.
        $validationRules = [
            'question_id' => 'required|exists:questions,id',
        ];

        if ($question->is_required) {
            $validationRules['answer_value'] = 'required|string';
        } else {
            $validationRules['answer_value'] = 'nullable|string';
        }

        $validated = $request->validate($validationRules);
        // ^^^ END OF FIX ^^^

        // Save the response. If optional and unanswered, save "Skipped".
        $session->responses()->create([
            'question_id' => $validated['question_id'],
            'answer_value' => $validated['answer_value'] ?? 'Skipped',
        ]);

        $nextQuestionOrder = $order + 1;
        $nextQuestionExists = $session->survey->questions()->where('order', $nextQuestionOrder)->exists();

        if ($nextQuestionExists) {
            return redirect()->route('public.survey.question.show', [
                'session_uuid' => $session_uuid,
                'order' => $nextQuestionOrder,
            ]);
        } else {
            $session->update(['completed_at' => now()]);
            return redirect()->route('public.survey.complete');
        }
    }

    /**
     * Display the survey completion page.
     */
   
}
