<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-VALuate - Survey Closed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <main class="bg-white w-full max-w-lg rounded-xl shadow-lg p-8 text-center">
        <!-- Lock Icon -->
        <div>
            <svg class="w-20 h-20 text-red-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-slate-800 mt-4">Survey Closed</h1>
        <p class="text-slate-600 mt-2 text-lg">
            The survey "<span class="font-semibold">{{ $survey->title }}</span>" is no longer accepting new responses.
        </p>
        <p class="text-slate-500 mt-4">Thank you for your interest.</p>

        <div class="mt-8">
            <a href="/" class="text-indigo-600 hover:text-indigo-800 font-semibold">Return to Homepage</a>
        </div>
    </main>

</body>
</html>
