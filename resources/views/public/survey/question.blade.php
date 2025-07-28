<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>e-VALuate - Question {{ $question->order }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 0.5rem; }
        .star-rating input[type="radio"] { display: none; }
        .star-rating label { cursor: pointer; color: #d1d5db; transition: color 0.2s; }
        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input[type="radio"]:checked ~ label { color: #f59e0b; }

        /* vvv SMOOTHER ANIMATION STYLES vvv */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(25px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to { opacity: 0; transform: translateY(-15px) scale(0.98); }
        }
        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .fade-out {
            animation: fadeOut 0.4s ease-in forwards;
        }
        /* ^^^ END OF SMOOTHER STYLES ^^^ */
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <main id="survey-container" class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-4 sm:p-8 fade-in">
        <header class="text-center mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">{{ $survey->title }}</h1>
        </header>

        <div class="mb-8">
            <div class="flex justify-between items-center mb-1">
                <p class="text-sm font-medium text-slate-600">Question {{ $question->order }} of {{ $totalQuestions }}</p>
                <p class="text-sm font-medium text-indigo-600">{{ round((($question->order - 1) / $totalQuestions) * 100) }}% Complete</p>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ (($question->order -1) / $totalQuestions) * 100 }}%"></div>
            </div>
        </div>

        <form id="question-form" method="POST" action="{{ route('public.survey.question.store', ['session_uuid' => $session_uuid, 'order' => $question->order]) }}">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">

            <fieldset>
                <legend class="text-center text-lg sm:text-xl font-semibold text-slate-800">
                    {{ $question->question_text }}
                    @if(!$question->is_required)
                        <span class="text-base font-normal text-gray-500">(Optional)</span>
                    @endif
                </legend>
                @if($question->description)
                    <p class="text-center text-sm text-gray-500 mt-2 mb-6">{{ $question->description }}</p>
                @endif

                <div class="space-y-4">
                    @if ($question->question_type === 'rating')
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="answer_value" value="{{ $i }}" @if($question->is_required) required @endif>
                                <label for="star{{ $i }}" title="{{ $i }} stars">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </label>
                            @endfor
                        </div>
                    @elseif ($question->question_type === 'text')
                        <textarea name="answer_value" rows="5" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm" placeholder="Type your answer here..." @if($question->is_required) required @endif></textarea>
                    @elseif ($question->question_type === 'multiple_choice')
                        @foreach ($question->options as $option)
                            <div>
                                <input type="radio" id="option_{{ Str::slug($option) }}" name="answer_value" value="{{ $option }}" class="hidden peer" @if($question->is_required) required @endif>
                                <label for="option_{{ Str::slug($option) }}" class="block p-4 border-2 border-slate-300 rounded-lg cursor-pointer peer-checked:border-indigo-600 hover:bg-slate-100">
                                    <span class="text-base sm:text-lg font-medium text-slate-700">{{ $option }}</span>
                                </label>
                            </div>
                        @endforeach
                    @elseif ($question->question_type === 'checkbox')
                        @foreach ($question->options as $option)
                            <div class="relative flex items-start">
                                <div class="flex h-6 items-center">
                                    <input id="checkbox_{{ Str::slug($option) }}" name="answer_value[]" value="{{ $option }}" type="checkbox" class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                </div>
                                <div class="ml-3 text-sm leading-6">
                                    <label for="checkbox_{{ Str::slug($option) }}" class="font-medium text-gray-900">{{ $option }}</label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </fieldset>

            <div class="mt-8 flex flex-col-reverse sm:flex-row items-center justify-center gap-4">
                @if ($question->order > 1)
                    <a href="{{ route('public.survey.question.show', ['session_uuid' => $session_uuid, 'order' => $question->order - 1]) }}" class="w-full sm:w-auto text-center font-bold text-lg py-3 px-8 sm:px-12 rounded-lg text-slate-600 hover:bg-slate-100">
                        Previous
                    </a>
                @endif
                <button type="submit" class="w-full sm:w-auto bg-[#1AADF3] text-white font-bold text-lg py-3 px-8 sm:px-12 rounded-lg hover:bg-[#1798D1] transition-colors duration-300">
                    @if ($question->order == $totalQuestions)
                        Finish Survey
                    @else
                        Next Question
                    @endif
                </button>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('question-form');
            const container = document.getElementById('survey-container');
            let isSubmitting = false;

            function submitWithAnimation() {
                if (isSubmitting) return;

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                isSubmitting = true;
                form.querySelectorAll('input, textarea, button, a').forEach(el => el.style.pointerEvents = 'none');
                container.classList.remove('fade-in');
                container.classList.add('fade-out');

                setTimeout(() => {
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => {
                        if (response.ok && response.redirected) {
                            window.location.href = response.url;
                        } else {
                            window.location.reload();
                        }
                    }).catch(error => {
                        console.error('Network Error:', error);
                        window.location.reload();
                    });
                }, 400); // Must match the fadeOut animation duration
            }

            // The auto-submit functionality has been completely removed.
            // All questions will now use the main submit button.

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                submitWithAnimation();
            });
        });
    </script>
</body>
</html>
