<div class="flex items-center justify-center min-h-[60vh] bg-white dark:bg-slate-900 px-6 mt-5 py-16 sm:py-24">
    <div class="text-center max-w-lg">
        {{-- Success Icon --}}
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/50">
            <svg class="h-10 w-10 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        {{-- Confirmation Text --}}
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Permohonan Berhasil Terkirim!</h2>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">
            Terima kasih.<br>Data peminjaman arsip Anda telah kami terima. Tim kami akan segera meninjau permohonan Anda dan akan memberikan konfirmasi lebih lanjut melalui email atau nomor telepon yang Anda daftarkan.
        </p>

        {{-- Back to Home Button --}}
        <div class="mt-8">
            <a href="{{ url('/') }}" class="inline-flex items-center rounded-md border border-transparent bg-sky-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
