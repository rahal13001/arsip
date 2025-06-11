<div x-data="{
    isOpen: false,
    theme: localStorage.getItem('theme') || 'system',

    setTheme(newTheme) {
        this.theme = newTheme;
        localStorage.setItem('theme', newTheme);

        if (newTheme === 'dark' || (newTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.dispatchEvent(new CustomEvent('dark-mode-toggled', { detail: newTheme }));
        this.isOpen = false;
    },

    init() {
        this.setTheme(this.theme);
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.theme === 'system') {
                this.setTheme('system');
            }
        });
        window.addEventListener('dark-mode-toggled', (event) => {
            this.theme = event.detail;
        });
    }
}" x-init="init()" class="relative mr-4">

    {{-- Dropdown Trigger Button --}}
    <button
        x-on:click="isOpen = !isOpen"
        type="button"
        class="flex items-center justify-center w-10 h-10 transition rounded-full fi-icon-btn fi-icon-btn-size-md text-gray-400 bg-gray-100/50 hover:bg-gray-200/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:text-gray-500 dark:bg-gray-800/50 dark:hover:bg-gray-700/50"
    >
        <span class="sr-only">Open theme options</span>

        {{-- Dynamic Icons based on current theme --}}
        <template x-if="theme === 'light'">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>
        </template>
        <template x-if="theme === 'dark'">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
        </template>
        <template x-if="theme === 'system'">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25A2.25 2.25 0 0 1 5.25 3h13.5A2.25 2.25 0 0 1 21 5.25Z" />
            </svg>
        </template>
    </button>

    {{-- Dropdown Panel --}}
    <div
        x-show="isOpen"
        x-on:click.away="isOpen = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-10 w-auto mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-700"
        style="display: none;"
    >
        <div class="p-2">
            <div class="grid grid-cols-3 gap-1 rounded-lg">
                @php
                    $items = [
                        'light' => 'sun',
                        'dark' => 'moon',
                        'system' => 'computer-desktop',
                    ];
                @endphp

                @foreach ($items as $themeChoice => $icon)
                    <button
                        type="button"
                        x-on:click.prevent="setTheme('{{ $themeChoice }}')"
                        :class="{
                            'bg-primary-500 text-white': theme === '{{ $themeChoice }}',
                            'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-900/60': theme !== '{{ $themeChoice }}'
                        }"
                        class="flex items-center justify-center w-full p-2 text-sm font-medium transition rounded-md"
                    >
                        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5" />
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
