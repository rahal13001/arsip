import './bootstrap';

// =================================================================
// LOGIKA GLOBAL (Berjalan di semua halaman)
// =================================================================

/**
 * Menginisialisasi fungsionalitas Dark Mode Toggle.
 * Berjalan secara global di setiap halaman yang memuat app.js.
 */
function initializeDarkMode() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Fungsi untuk menerapkan tema yang dipilih
    function applyTheme(isDark) {
        document.documentElement.classList.toggle('dark', isDark);
        if (themeToggleLightIcon) themeToggleLightIcon.classList.toggle('hidden', !isDark);
        if (themeToggleDarkIcon) themeToggleDarkIcon.classList.toggle('hidden', isDark);
    }

    // Periksa tema yang tersimpan atau preferensi sistem saat halaman dimuat
    const savedTheme = localStorage.getItem('color-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = savedTheme === 'dark' || (savedTheme === null && prefersDark);

    applyTheme(isDark);

    // Tambahkan event listener untuk tombol toggle
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const isCurrentlyDark = document.documentElement.classList.contains('dark');
            applyTheme(!isCurrentlyDark);
            localStorage.setItem('color-theme', isCurrentlyDark ? 'light' : 'dark');
        });
    }
}

// Panggil fungsi global saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    initializeDarkMode();
});

