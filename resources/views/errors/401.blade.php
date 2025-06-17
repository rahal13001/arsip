<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Akses Ditolak - Si Panda</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300">
<div class="flex flex-col items-center justify-center min-h-screen px-6 py-12">
    <div class="max-w-md text-center">

        {{-- Error Code --}}
        <p class="text-6xl font-extrabold text-sky-600 dark:text-sky-400" style="font-size: 8rem; line-height: 1;">401</p>

        {{-- Error Title --}}
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-4xl">Akses Ditolak.</h1>

        {{-- Error Description --}}
        <p class="mt-4 text-base text-slate-600 dark:text-slate-400">
            Mohon maaf, Anda harus login terlebih dahulu untuk mengakses halaman ini. Sesi Anda mungkin telah berakhir atau Anda tidak memiliki izin yang diperlukan.
        </p>

        {{-- Action Buttons --}}
        <div class="mt-16 flex items-center justify-center gap-x-6">
            <a href="{{ url('/') }}" class="rounded-md bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 transition-colors">
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
</body>
</html>
