<?php

namespace App\Livewire\Guest;

use Livewire\Component;

class ArchiveLending extends Component
{
    // Properti publik untuk menampung data dari form
    public $applicant_name;
    public $applicant_position;
    public $applicant_organization;
    public $applicant_address;
    public $applicant_phone;
    public $applicant_email;
    public $applicant_id_number;
    public $date_of_lending;
    public $lending_until;
    public $applicant_note;

    /**
     * Aturan validasi yang akan diterapkan sebelum menyimpan.
     */
    protected $rules = [
        'applicant_name' => 'required|string|max:255',
        'applicant_position' => 'required|string|max:255',
        'applicant_organization' => 'required|string|max:255',
        'applicant_address' => 'required|string',
        'applicant_phone' => 'required|string|max:20',
        'applicant_email' => 'required|email|max:255',
        'applicant_id_number' => 'required|string|max:255',
        'date_of_lending' => 'required|date',
        'lending_until' => 'required|date|after_or_equal:date_of_lending',
        'applicant_note' => 'nullable|string|max:1000',
    ];

    /**
     * Menyesuaikan pesan validasi agar lebih ramah pengguna (opsional).
     */
    protected $messages = [
        'applicant_name.required' => 'Nama pemohon wajib diisi.',
        'applicant_email.required' => 'Alamat email wajib diisi.',
        'applicant_email.email' => 'Format email tidak valid.',
        'lending_until.after_or_equal' => 'Tanggal pengembalian harus sama atau setelah tanggal peminjaman.',
        // Tambahkan pesan kustom lainnya di sini
    ];

    /**
     * Fungsi yang dipanggil saat form di-submit.
     */
    public function save()
    {
        // Jalankan validasi
        $validatedData = $this->validate();

        try {
            // Logika untuk menyimpan data ke database.
            // Pastikan Anda sudah membuat model `ArchiveLoan` dan migrasinya.
            // Contoh:
            // ArchiveLoan::create($validatedData);

            // Tampilkan pesan sukses
            session()->flash('success', 'Permohonan peminjaman arsip Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');

            // Kosongkan kembali form setelah berhasil
            $this->reset();

        } catch (\Exception $e) {
            // Tampilkan pesan error jika terjadi masalah saat menyimpan
            session()->flash('error', 'Terjadi kesalahan. Gagal mengirim permohonan.');
        }
    }

    public function render()
    {
        return view('livewire.guest.archive-lending')
            ->layout('components.layouts.app');
    }
}
