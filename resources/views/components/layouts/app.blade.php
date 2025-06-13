<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Si Panda - Arsip Digital LPSPL Sorong</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite Assets are correctly pointing to the default app.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom styles to use the Inter font */
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }
        /* Style for the timeline line for better visibility in both modes */
        .timeline-line {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: #e2e8f0; /* slate-200 */
        }
        .dark .timeline-line {
            background-color: #334155; /* slate-700 */
        }
        @media (max-width: 768px) {
            .timeline-line {
                left: 1.5rem; /* Adjusted for mobile view */
            }
        }
        /* Animation Styles */
        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    @livewireStyles
</head>
<body class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300">
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <!-- Logo untuk Light Mode (default) -->
                <img src="{{ asset('img/Sipanda.png') }}" alt="Logo Si Panda" class="h-10 dark:hidden" onerror="this.onerror=null;this.src='https://placehold.co/180x40/ffffff/000000?text=Logo+SiPanda';">
                <!-- Logo untuk Dark Mode -->
                <img src="{{ asset('img/Sipandablack.png') }}" alt="Logo Si Panda Dark" class="h-10 hidden dark:block" onerror="this.onerror=null;this.src='https://placehold.co/180x40/0f172a/ffffff?text=Logo+SiPanda';">
            </div>
            <div class="flex items-center space-x-4">
                <a href="/arsip" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors duration-300 font-semibold text-sm sm:text-base">
                    Lihat Arsip
                </a>
                <button id="theme-toggle" type="button" class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700 rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>
            </div>
        </div>
    </header>
    <main>
        {{ $slot }}
    </main>
@livewireScripts
</body>
