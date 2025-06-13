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
</head>
<body class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300">

<!-- Header Navigation -->
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

<!-- Main Content -->
<main>
    <!-- Hero Section -->
    <section class="relative h-[60vh] flex items-center justify-center text-center overflow-hidden">
        <!-- Background Image using <img> tag -->
        <img src="{{ asset('img/arsipfoto.png') }}" alt="Pemandangan bawah laut" class="absolute top-0 left-0 w-full h-full object-cover z-0">

        <!-- Overlay with inline RGBA style for reliability -->
        <div class="absolute inset-0 z-10" style="background-color: rgba(0, 0, 0, 0.5);"></div>

        <!-- Content -->
        <div class="relative z-20 px-6">
            <h2 class="text-4xl md:text-6xl font-extrabold text-white leading-tight tracking-tight" style="text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);">Selamat Datang di Si Panda</h2>
            <p class="mt-4 text-lg md:text-xl text-slate-200 max-w-3xl mx-auto" style="text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);">Sistem Penginputan dan Pengelolaan Data Kearsipan LPSPL Sorong.</p>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="py-16 sm:py-24 bg-white dark:bg-slate-900 fade-in-section">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-sky-600 dark:text-sky-400">Tentang Aplikasi Si Panda</h3>
                <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">Pengelolaan arsip yang efektif dan efisien melalui digitalisasi.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h4 class="text-2xl font-semibold mb-4 text-slate-800 dark:text-white">Apa itu Si Panda?</h4>
                    <p class="mb-4 leading-relaxed">
                        Sipanda merupakan Sistem Penginputan dan Pengelolaan Data Kearsipan. Sistem ini dibangun untuk mengumpulkan dan mendukung digitalisasi data sehingga pengelolaan arsip di lingkungan Loka Pengelolaan Sumberdaya Pesisir dan Laut (LPSPL) Sorong dapat berjalan secara efektif dan efisien.
                    </p>
                    <p class="leading-relaxed">
                        Inisiatif ini adalah wujud komitmen kami dalam mengelola aset informasi secara modern untuk mendukung pengambilan keputusan, pengelolaan sumber daya, serta transparansi publik.
                    </p>
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img src="{{ asset('img/serahterimaarsip.jpg') }}" alt="Serah Terima Arsip" class="h-auto w-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 sm:py-24 bg-slate-50 dark:bg-slate-800 fade-in-section">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-slate-800 dark:text-white">Fitur Utama</h3>
                <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">Mengapa menggunakan Si Panda?</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-slate-700 p-6 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <svg class="h-8 w-8 text-sky-600 dark:text-sky-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" /></svg>
                    <h5 class="font-bold text-lg text-slate-800 dark:text-white">Keamanan Data</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Melindungi arsip dari kerusakan fisik dan kehilangan.</p>
                </div>
                <div class="bg-white dark:bg-slate-700 p-6 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <svg class="h-8 w-8 text-sky-600 dark:text-sky-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    <h5 class="font-bold text-lg text-slate-800 dark:text-white">Aksesibilitas</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Memudahkan pencarian dan akses informasi kapan saja.</p>
                </div>
                <div class="bg-white dark:bg-slate-700 p-6 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <svg class="h-8 w-8 text-sky-600 dark:text-sky-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <h5 class="font-bold text-lg text-slate-800 dark:text-white">Efisiensi Kerja</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Mempercepat proses kerja dengan manajemen dokumen modern.</p>
                </div>
                <div class="bg-white dark:bg-slate-700 p-6 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <svg class="h-8 w-8 text-sky-600 dark:text-sky-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                    <h5 class="font-bold text-lg text-slate-800 dark:text-white">Pelestarian</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Menjaga nilai historis dan ilmiah untuk generasi mendatang.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Zona Integritas Section -->
    <section class="py-16 sm:py-24 bg-white dark:bg-slate-900 fade-in-section">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left">
                    <span class="text-sm font-bold tracking-wider text-sky-600 dark:text-sky-400 uppercase">Komitmen Kami</span>
                    <h3 class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Pembangunan Zona Integritas</h3>
                    <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">LPSPL Sorong berkomitmen penuh untuk mewujudkan Wilayah Bebas dari Korupsi (WBK) dan Wilayah Birokrasi Bersih dan Melayani (WBBM) melalui reformasi birokrasi yang berkelanjutan.</p>
                </div>
                <div class="flex justify-center items-center space-x-6">
                    <img src="https://i.imgur.com/v9Y3u9g.png" alt="Logo Zona Integritas" class="h-32" onerror="this.onerror=null;this.src='https://placehold.co/128x128/ffffff/000000?text=ZI';">
                    <img src="https://i.imgur.com/k2j4a2W.png" alt="Logo WBK" class="h-32" onerror="this.onerror=null;this.src='https://placehold.co/128x128/ffffff/000000?text=WBK';">
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-16 sm:py-24 bg-slate-50 dark:bg-slate-800 fade-in-section">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-sky-600 dark:text-sky-400">Galeri Konservasi</h3>
                <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">Upaya kami dalam menjaga dan melestarikan kekayaan laut Indonesia.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img class="h-auto w-full max-w-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{asset('img/selam.jpg')}}" alt="Transplantasi terumbu karang">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img class="h-auto w-full max-w-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{asset('img/anakanpenyu.jpg')}}" alt="Tukik di Malaumkarta">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img class="h-auto w-full max-w-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{asset('img/telurpenyumalaumkarta.jpg')}}" alt="Hutan mangrove di pesisir Papua">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img class="h-auto w-full max-w-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{asset('img/nemo.jpg')}}" alt="Ikan badut di anemon">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img class="h-auto w-full max-w-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{asset('img/terdampar.jpg')}}" alt="Patroli pengawasan laut oleh KKP">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img class="h-auto w-full max-w-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{asset('img/sosialisasitankei.jpg')}}" alt="Sosialisasi kepada masyarakat pesisir">
                </div>
            </div>
        </div>
    </section>

    <!-- History of Archiving Section -->
    <section class="py-16 sm:py-24 bg-white dark:bg-slate-900 fade-in-section">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Jejak Sejarah Kearsipan Indonesia</h3>
                <p class="mt-3 text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Memahami perjalanan panjang lembaga kearsipan yang membentuk fondasi sistem kearsipan nasional saat ini.</p>
            </div>

            <!-- Timeline Container -->
            <div class="relative wrap overflow-hidden p-4 md:p-10 h-full">
                <div class="timeline-line hidden md:block"></div>
                <div id="timeline-container"></div>
            </div>

            <!-- Leaders Section -->
            <div class="mt-16 pt-12 border-t border-slate-200 dark:border-slate-700">
                <h3 class="text-2xl font-bold text-center mb-8 text-slate-800 dark:text-white">Pimpinan Arsip Nasional RI dari Masa ke Masa</h3>
                <div class="max-w-4xl mx-auto">
                    <ol class="list-decimal list-inside space-y-2 text-center sm:text-left sm:columns-2">
                        <li>DR. R. Soekanto (1951 - 1957)</li>
                        <li>Drs. R. Mohammad Ali (1957 - 1970)</li>
                        <li>Dra. Soemartini (1971 - 1992)</li>
                        <li>DR. Noerhadi Magetsari (1992 - 1998)</li>
                        <li>DR. Mukhlis Paeni (1998 - 2003)</li>
                        <li>Drs. Oman Sachroni, M.Si. (2003 - 2004)</li>
                        <li>Drs. Djoko Utomo, MA (2004 - 2009)</li>
                        <li>M. Asichin, S.H., M.Hum (2010 - 2013)</li>
                        <li>Dr. Mustari Irawan, MPA (2013 - 2019)</li>
                        <li>Drs. Imam Gunarto, M.Hum (2021 - sekarang)</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-slate-800 dark:bg-slate-900 text-white">
    <div class="container mx-auto px-6 py-8 text-center">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Loka Pengelolaan Sumberdaya Pesisir dan Laut Sorong.</p>
        <p class="text-sm text-slate-400 mt-1">Kementerian Kelautan dan Perikanan Republik Indonesia</p>
    </div>
</footer>

<script>
    // LOGIKA SPESIFIK HANYA UNTUK HALAMAN INI
    document.addEventListener('DOMContentLoaded', function() {

        // --- ANIMATION ON SCROLL LOGIC ---
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, {
            threshold: 0.1 // Trigger when 10% of the element is visible
        });

        const sections = document.querySelectorAll('.fade-in-section');
        sections.forEach(section => {
            observer.observe(section);
        });

        // --- TIMELINE LOGIC ---
        const timelineContainer = document.getElementById('timeline-container');
        if (!timelineContainer) return; // Keluar jika elemen tidak ditemukan

        const timelineData = [
            { id: 1, align: 'left', title: 'LANDSARCHIEF (1892)', description: 'Didirikan 28 Januari 1892 oleh Pemerintah Hindia Belanda, dipimpin oleh Mr. Jacob Anne van der Chijs. Bertugas memelihara arsip VOC hingga Hindia Belanda untuk kepentingan administrasi dan ilmu pengetahuan.' },
            { id: 2, align: 'right', title: 'KOBUNSJOKAN (1942 - 1945)', description: 'Pada masa pendudukan Jepang, Landsarchief berganti nama menjadi Kobunsjokan. Masa ini dianggap sepi dalam dunia kearsipan, namun lembaga ini tetap penting bagi orang Belanda untuk mencari bukti keturunan.' },
            { id: 3, align: 'left', title: 'ARSIP NEGERI (1945 - 1947)', description: 'Setelah proklamasi, lembaga kearsipan diambil alih Pemerintah RI, ditempatkan di bawah Kementerian Pendidikan, dan dinamai Arsip Negeri hingga Agresi Militer Belanda I.' },
            { id: 4, align: 'right', title: 'ARSIP NEGARA (1950 - 1959)', description: 'Setelah pengakuan kedaulatan, lembaga kembali ke tangan RI. Nama diubah menjadi Arsip Negara RIS, dipimpin pertama kali oleh orang Indonesia, Prof. R. Soekanto.' },
            { id: 5, align: 'left', 'title': 'ARSIP NASIONAL (1959 - 1967)', description: 'Nama berubah menjadi Arsip Nasional. Tugas dan fungsinya diperluas untuk mencakup kearsipan statis dan dinamis melalui Peraturan Presiden No. 19 tahun 1961.' },
            { id: 6, align: 'right', title: 'ARSIP NASIONAL REPUBLIK INDONESIA (1967 - Sekarang)', description: 'Ditetapkan sebagai Lembaga Pemerintah Non Departemen (LPND) yang bertanggung jawab langsung kepada Presiden. Diperkuat oleh UU No. 7/1971 dan Keppres No. 26/1974, menjadi ANRI yang kita kenal saat ini.' }
        ];

        timelineContainer.innerHTML = ''; // Kosongkan kontainer untuk menghindari duplikasi
        timelineData.forEach(item => {
            const isLeft = item.align === 'left';
            const itemHtml = `
                <div class="mb-8 flex justify-between ${isLeft ? 'md:flex-row-reverse' : ''} items-center w-full fade-in-section">
                    <div class="order-1 md:w-5/12"></div>
                    <div class="z-20 flex items-center order-1 bg-sky-600 shadow-xl w-12 h-12 rounded-full">
                        <h1 class="mx-auto font-semibold text-lg text-white">${item.id}</h1>
                    </div>
                    <div class="order-1 bg-white dark:bg-slate-700 rounded-lg shadow-lg w-full md:w-5/12 px-6 py-4 transition-all duration-300 hover:shadow-xl hover:scale-105">
                        <h4 class="font-bold text-sky-600 dark:text-sky-400 text-lg">${item.title}</h4>
                        <p class="mt-2 text-sm leading-snug tracking-wide text-slate-600 dark:text-slate-300">${item.description}</p>
                    </div>
                </div>
            `;
            timelineContainer.insertAdjacentHTML('beforeend', itemHtml);
        });

        // Observe timeline items after they are created
        const timelineItems = timelineContainer.querySelectorAll('.fade-in-section');
        timelineItems.forEach(item => {
            observer.observe(item);
        });
    });
</script>
</body>
</html>
