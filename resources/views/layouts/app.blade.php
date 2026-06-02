<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} - Shipment System</title>
    <script>
        (function() {
            if (JSON.parse(localStorage.getItem('darkMode') ?? 'false')) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{
    darkMode: false,
    sidebarToggle: false,
    init() {
        this.darkMode = JSON.parse(localStorage.getItem('darkMode') ?? 'false');
        this.$watch('darkMode', val => {
            localStorage.setItem('darkMode', JSON.stringify(val));
            document.documentElement.classList.toggle('dark', val);
        });
    }
}" :class="{ 'dark bg-gray-900': darkMode }" class="bg-gray-50 dark:bg-gray-900">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Content Area --}}
        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">

            {{-- Header --}}
            @include('partials.navbar')

            {{-- Flash Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mx-6 mt-4 flex items-center gap-3 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mx-6 mt-4 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Main Content --}}
            <main>
                <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

</body>
<script>
    document.getElementById('myForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');

        if (btn.disabled) {
            e.preventDefault(); // cegah submit kedua
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Loading...';
    });
</script>

</html>
