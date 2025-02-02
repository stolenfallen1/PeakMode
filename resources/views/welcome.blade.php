<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PeakMode</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased dark:bg-gray-900">
        <div class="bg-gray-50 dark:bg-gray-900">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-blue-500 selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">PeakMode</h1>
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-gray-900 ring-1 ring-transparent transition hover:text-gray-700 dark:text-white dark:hover:text-gray-300">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-gray-900 ring-1 ring-transparent transition hover:text-gray-700 dark:text-white dark:hover:text-gray-300">
                                        Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-gray-900 ring-1 ring-transparent transition hover:text-gray-700 dark:text-white dark:hover:text-gray-300">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <div class="text-center lg:text-left">
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white sm:text-4xl">Track Your Fitness Journey</h2>
                                <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                                    Take control of your workouts with our comprehensive tracking system. Plan, monitor, and achieve your fitness goals all in one place.
                                </p>
                                <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                                    <a href="{{ route('register') }}"
                                        class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                        Get started
                                    </a>
                                    <a href="#features" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">
                                        Learn more <span aria-hidden="true">→</span>
                                    </a>
                                </div>
                            </div>
                            <div class="relative">
                                <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden">
                                    <picture>
                                        <source media="(min-width: 768px)" srcset="{{ asset('images/large-homepage-img.jpg') }}">
                                        <img src="{{ asset('images/small-homepage-img.jpg') }}" 
                                            alt="Workout tracking demonstration" 
                                            class="w-full h-full object-cover">
                                    </picture>
                                </div>
                            </div>
                        </div>

                        <!-- Features Section -->
                        <div id="features" class="mt-24 grid grid-cols-1 gap-8 lg:grid-cols-3">
                            <div class="rounded-xl bg-white p-8 shadow-lg dark:bg-gray-800">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Workout Planning</h3>
                                <p class="mt-4 text-gray-600 dark:text-gray-300">Create and customize your workout routines with our intuitive planning tools.</p>
                            </div>
                            <div class="rounded-xl bg-white p-8 shadow-lg dark:bg-gray-800">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Progress Tracking</h3>
                                <p class="mt-4 text-gray-600 dark:text-gray-300">Monitor your progress and see your improvements over time with detailed analytics.</p>
                            </div>
                            <div class="rounded-xl bg-white p-8 shadow-lg dark:bg-gray-800">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Exercise Library</h3>
                                <p class="mt-4 text-gray-600 dark:text-gray-300">Access our comprehensive library of exercises with detailed instructions.</p>
                            </div>
                        </div>
                    </main>

                    <footer class="mt-16 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>&copy; {{ date('Y') }} PeakMode. All rights reserved.</p>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>