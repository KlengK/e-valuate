<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-VALuate - Question {{ $question->order }}</title>
    
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom styles for star rating */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 0.5rem;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            cursor: pointer;
            color: #d1d5db; /* gray-300 */
            transition: color 0.2s;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #f59e0b; /* amber-500 */
        }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <main class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 sm:p-8">
        <!-- Survey Header -->
        <header class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">{{ $survey->title }}</h1>
        </header>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-1">
                <p class="text-sm font-medium text-slate-600">Question {{ $question->order }} of {{ $totalQuestions }}</p>
                <p class="text-sm font-medium text-indigo-600">{{ round(($question->order / $totalQuestions) * 100) }}%</p>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ ($question->order / $totalQuestions) * 100 }}%"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('public.survey.question.store', ['session_uuid' => $session_uuid, 'order' => $question->order]) }}">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">

            <fieldset>
                <legend class="text-center text-xl font-semibold text-slate-800 mb-6">{{ $question->question_text }}</legend>

                <div class="space-y-4">
                    <!-- RATING (STARS) -->
                    @if ($question->question_type === 'rating')
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="answer_value" value="{{ $i }}" required>
                                <label for="star{{ $i }}" title="{{ $i }} stars">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </label>
                            @endfor
                        </div>

                    <!-- OPEN TEXT -->
                    @elseif ($question->question_type === 'text')
                        <textarea name="answer_value" rows="5" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Type your answer here..." required></textarea>

                    <!-- MULTIPLE CHOICE -->
                    @elseif ($question->question_type === 'multiple_choice' && !empty($question->options))
                        @foreach ($question->options as $option)
                            <div>
                                <input type="radio" id="option_{{ Str::slug($option) }}" name="answer_value" value="{{ $option }}" class="hidden peer" required>
                                <label for="option_{{ Str::slug($option) }}" class="block p-4 border-2 border-slate-300 rounded-lg cursor-pointer transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:bg-slate-100">
                                    <span class="text-lg font-medium text-slate-700">{{ $option }}</span>
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </fieldset>

            <div class="mt-8 text-center">
                <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white font-bold text-lg py-3 px-12 rounded-lg hover:bg-indigo-700 transition-colors duration-300">
                    @if ($question->order == $totalQuestions)
                        Finish Survey
                    @else
                        Next Question
                    @endif
                </button>
            </div>
        </form>

        <!-- Footer -->
        <footer class="text-center mt-8">
            <p class="text-sm text-slate-400">Powered by e-VALuate</p>
        </footer>
    </main>

</body>
</html>
