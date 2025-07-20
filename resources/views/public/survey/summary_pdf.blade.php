<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Survey Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 24px; }
        h2 { font-size: 18px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .survey-details { margin-bottom: 20px; }
        .question-block { margin-bottom: 20px; page-break-inside: avoid; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="survey-details">
        <h1>Survey Report: {{ $survey->title }}</h1>
        <p><strong>Total Completions:</strong> {{ $totalCompletions }}</p>
    </div>

    @foreach($reportData as $question)
        <div class="question-block">
            <h2>{{ $question['question_text'] }}</h2>
            @if($question['question_type'] === 'rating')
                <p><strong>Average Rating:</strong> {{ $question['results']['average'] }} ★</p>
            @elseif($question['question_type'] === 'multiple_choice')
                <table>
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($question['results'] as $option => $count)
                        <tr>
                            <td>{{ $option }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($question['question_type'] === 'text')
                <p><strong>Responses:</strong></p>
                <ul>
                    @foreach($question['results'] as $answer)
                    <li>{{ $answer }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach
</body>
</html>
