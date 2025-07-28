<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveySummaryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    public function collection()
    {
        return $this->survey->questions()->with('responses')->get();
    }

    public function headings(): array
    {
        return [ 'Question Order', 'Question Text', 'Question Type', 'Result Type', 'Result Value' ];
    }

    public function map($question): array
    {
        $rows = [];
        if ($question->question_type === 'rating') {
            $ratings = $question->responses->pluck('answer_value')->map(fn($val) => (int)$val);
            $average = $ratings->avg() ? round($ratings->avg(), 2) : 0;
            $rows[] = [ $question->order, $question->question_text, $question->question_type, 'Average Rating', $average ];
        } elseif ($question->question_type === 'multiple_choice') {
            $counts = $question->responses->pluck('answer_value')->countBy();
            foreach ($counts as $option => $count) {
                $rows[] = [ $question->order, $question->question_text, $question->question_type, $option, $count ];
            }
        } elseif ($question->question_type === 'text') {
            foreach ($question->responses as $response) {
                 $rows[] = [ $question->order, $question->question_text, $question->question_type, 'Individual Response', $response->answer_value ];
            }
        }
        if (empty($rows)) {
            $rows[] = [ $question->order, $question->question_text, $question->question_type, 'No Responses', '' ];
        }
        return $rows;
    }
}
