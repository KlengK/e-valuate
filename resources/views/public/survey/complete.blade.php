<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-VALuate - Survey Complete!</title>
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
        /* vvv NEW STYLE FOR CONFETTI CANVAS vvv */
        #confetti-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            pointer-events: none;
        }
        /* ^^^ END OF NEW STYLE ^^^ */
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <!-- vvv NEW CANVAS ELEMENT vvv -->
    <canvas id="confetti-canvas"></canvas>
    <!-- ^^^ END OF NEW CANVAS ELEMENT ^^^ -->

    <main class="bg-white w-full max-w-lg rounded-xl shadow-lg p-8 text-center">
        <!-- Checkmark Icon -->
        <div>
            <svg class="w-20 h-20 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <h1 class="text-3xl font-bold text-slate-800 mt-4">Thank You!</h1>
        <p class="text-slate-600 mt-2 text-lg">Your feedback has been received.</p>
        <p class="text-slate-500 mt-4">Your responses will help us improve the library for everyone.</p>

        <div class="mt-8">
            <a href="/" class="text-indigo-600 hover:text-indigo-800 font-semibold">Return to Homepage</a>
        </div>
    </main>

    <!-- vvv NEW SCRIPT FOR CONFETTI vvv -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('confetti-canvas');
            const myConfetti = confetti.create(canvas, {
                resize: true,
                useWorker: true
            });

            // Fire a burst of confetti
            myConfetti({
                particleCount: 150,
                spread: 180,
                origin: { y: 0.6 }
            });
        });
    </script>
    <!-- ^^^ END OF NEW SCRIPT ^^^ -->

</body>
</html>
