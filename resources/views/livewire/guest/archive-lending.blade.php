<div>
    {{-- Karena ini komponen Livewire, semua elemen harus berada di dalam satu div root --}}
    <div class="py-16 sm:py-24 bg-white dark:bg-slate-900">
        <div class="container mx-auto px-6">

            {{-- Header Halaman --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-sky-600 dark:text-sky-400 tracking-tight">Formulir Peminjaman Arsip</h2>
                <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">Silakan isi data di bawah ini untuk melakukan permohonan peminjaman arsip.</p>
            </div>

            {{-- Card Formulir --}}
            <div class="max-w-4xl mx-auto bg-slate-50 dark:bg-slate-800 p-8 rounded-2xl shadow-lg">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">

                        {{-- Nama Pemohon --}}
                        <div class="relative">
                            <input type="text" id="applicant_name" wire:model.defer="applicant_name" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" " />
                            <label for="applicant_name" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Nama Lengkap Pemohon
                            </label>
                            @error('applicant_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Jabatan --}}
                        <div class="relative">
                            <input type="text" id="applicant_position" wire:model.defer="applicant_position" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" " />
                            <label for="applicant_position" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Jabatan
                            </label>
                            @error('applicant_position') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Instansi/Organisasi --}}
                        <div class="relative md:col-span-2">
                            <input type="text" id="applicant_organization" wire:model.defer="applicant_organization" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" " />
                            <label for="applicant_organization" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Asal Instansi / Organisasi
                            </label>
                            @error('applicant_organization') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="relative md:col-span-2">
                            <textarea id="applicant_address" wire:model.defer="applicant_address" rows="3" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" "></textarea>
                            <label for="applicant_address" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-5 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Alamat Lengkap
                            </label>
                            @error('applicant_address') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div class="relative">
                            <input type="tel" id="applicant_phone" wire:model.defer="applicant_phone" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" " />
                            <label for="applicant_phone" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Nomor Telepon (WhatsApp)
                            </label>
                            @error('applicant_phone') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="relative">
                            <input type="email" id="applicant_email" wire:model.defer="applicant_email" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" " />
                            <label for="applicant_email" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Alamat Email
                            </label>
                            @error('applicant_email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nomor Identitas --}}
                        <div class="relative md:col-span-2">
                            <input type="text" id="applicant_id_number" wire:model.defer="applicant_id_number" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" " />
                            <label for="applicant_id_number" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Nomor Identitas (KTP/SIM/Paspor)
                            </label>
                            @error('applicant_id_number') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tanggal Pinjam --}}
                        <div class="relative">
                            <input type="date" id="date_of_lending" wire:model.defer="date_of_lending" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" />
                            <label for="date_of_lending" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400">
                                Tanggal Peminjaman
                            </label>
                            @error('date_of_lending') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tanggal Kembali --}}
                        <div class="relative">
                            <input type="date" id="lending_until" wire:model.defer="lending_until" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" />
                            <label for="lending_until" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400">
                                Tanggal Pengembalian
                            </label>
                            @error('lending_until') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Keperluan --}}
                        <div class="relative md:col-span-2">
                            <textarea id="applicant_note" wire:model.defer="applicant_note" rows="4" class="peer block w-full appearance-none rounded-md border border-slate-300 bg-transparent px-3 py-2.5 text-slate-900 dark:text-white dark:border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-0 sm:text-sm" placeholder=" "></textarea>
                            <label for="applicant_note" class="absolute top-2 left-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-slate-50 dark:bg-slate-800 px-2 text-sm text-slate-600 dark:text-slate-400 duration-300 peer-placeholder-shown:top-5 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:px-2 peer-focus:text-sky-600 dark:peer-focus:text-sky-500">
                                Keperluan / Catatan
                            </label>
                            @error('applicant_note') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="mt-8 pt-5 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex justify-end">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent bg-sky-600 py-3 px-6 text-base font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-colors">
                                <span wire:loading.remove>Kirim Permohonan</span>
                                <span wire:loading>Mengirim...</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
