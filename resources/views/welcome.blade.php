<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eVALuate - Valenzuela City Library</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* gray-50 */
        }
        /* vvv THIS IS THE UPDATED PART vvv */
        .hero-bg {
            /* We stack multiple backgrounds. The gradient is on top of the image. */
            background-image: 
                linear-gradient(-90deg, rgba(84, 122, 226, 0.8), rgba(59, 130, 246, 0.8), rgba(226, 46, 46, 0.8), rgba(42, 141, 253, 0.8)),
                url("{{ asset('images/hero-background.png') }}");
            background-size: 400% 400%, cover; /* Size for gradient animation and cover for the image */
            background-position: 0% 50%, center;
            background-repeat: no-repeat;
            animation: gradient 15s ease infinite;
        }
            .gradient-bg {
                background: linear-gradient(-90deg, rgba(84, 122, 226, 0.8), rgba(59, 130, 246, 0.8), rgba(226, 46, 46, 0.8), rgba(42, 141, 253, 0.8));
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }
        @keyframes gradient {
            0% { background-position: 0% 50%, center; }
            50% { background-position: 100% 50%, center; }
            100% { background-position: 0% 50%, center; }
        }
        /* ^^^ END OF UPDATED PART ^^^ */
        .scroll-animation {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .scroll-animation.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="antialiased">

    <div class="relative overflow-hidden">
        <!-- Hero Section -->
        <section class="relative hero-bg min-h-screen flex items-center justify-center p-6">
            <!-- Floating Navigation -->
            <nav class="absolute top-0 left-0 right-0 z-10 p-6">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logoo.png') }}" alt="ValACE Logo" class="h-10 w-auto">
                        <!-- Text color changed back to white -->
                        <span class="text-2xl font-bold text-white"></span>
                    </div>
                    <!-- Button colors changed back to light theme -->
                    <a href="{{ route('login') }}" class="border border-white/50 text-white font-semibold py-2 px-5 rounded-full hover:bg-white hover:text-blue-800 transition-colors duration-300">
                    Login
                    </a>
                </div>
            </nav>

            <div class="text-center z-10">
                <!-- Text colors changed back to light theme -->
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight text-white">eVALuate</h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-blue-100">
                    The official survey platform of the Valenzuela City Library (ValACE), designed to help you create the ideal survey. From feedback to foresight—let's shape a better VALenzuela together!                </p>
                <a href="{{ route('login') }}" class="mt-8 inline-block bg-white text-[#00104A] font-bold py-3 px-8 rounded-full shadow-lg hover:bg-blue-100 transform hover:scale-105 transition-transform duration-300">
                    Access Your Dashboard
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-white py-20 sm:py-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Feature 1: Create -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center scroll-animation">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-[#00104A]">Create your Ideal Survey.</h2>
                        <p class="mt-4 text-lg text-[#00104A]">
                            The Valenzuela City Library proudly introduces a dynamic and intuitive survey builder that goes beyond basic forms. Create engaging, gamified surveys with multiple question types, optional descriptions, and flexible response settings to gather meaningful feedback.                        </p>
                    </div>
                   <img src="{{ asset('images/1.png') }}" alt="Create Survey UI Screenshot" class="rounded-xl shadow-lg">
                </div>

                <!-- Feature 2: Share -->
                <div class="mt-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center scroll-animation">
                    <img src="{{ asset('images/2.png') }}" alt="Share Survey UI Screenshot" class="rounded-xl shadow-lg lg:order-last">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-[#00104A]">Reach your Target Audience.</h2>
                        <p class="mt-4 text-lg text-[#00104A]">
                        Easily generate shareable links and downloadable QR codes, complete with your logo—ideal for posters, table displays, and digital screens.                        </p>
                    </div>
                </div>

                <!-- Feature 3: Analyze -->
                <div class="mt-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center scroll-animation">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-[#00104A]">Turn Feedback into Insight.</h2>
                        <p class="mt-4 text-lg text-[#00104A]">
                            Go beyond data collection with an intelligent dashboard that automatically generates visual reports, including charts and individual response logs. Export results in CSV or PDF format for documentation and analysis.                        </p>
                    </div>
                   <img src="{{ asset('images/3.png') }}" alt="Analyze Reports UI Screenshot" class="rounded-xl shadow-lg">
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="gradient-bg py-20 sm:py-24 text-white">
            <div class="max-w-4xl mx-auto text-center px-6">
                <h2 class="text-3xl font-bold tracking-tight">Ready to Make Data-Driven Decisions?</h2>
                <a href="{{ route('login') }}" class="mt-8 inline-block bg-white text-slate-800 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-slate-100 transform hover:scale-105 transition-transform duration-300">
                Login
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#00104A] text-slate-400 py-8">
            <div class="max-w-7xl mx-auto px-6 text-center text-white">
                <p>&copy; {{ date('Y') }} Valenzuela City Public Library. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        // Simple scroll animation
        const scrollAnimations = document.querySelectorAll('.scroll-animation');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.1 });

        scrollAnimations.forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
