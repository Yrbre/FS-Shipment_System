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
    @livewireStyles
</head>

<body x-data="{
    darkMode: $persist(false).as('darkMode'),
    sidebarToggle: $persist(false).as('sidebarToggle'),
    init() {
        this.$watch('darkMode', val => {
            document.documentElement.classList.toggle('dark', val);
        });
        document.documentElement.classList.toggle('dark', this.darkMode);
    }
}" :class="{ 'dark bg-gray-900': darkMode }" class="bg-gray-50 dark:bg-gray-900">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Content Area --}}
        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">

            {{-- Header --}}
            @include('partials.navbar')



            {{-- Main Content --}}
            <main>
                <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>
    @livewireScripts
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('myForm');
            if (!form) return; // ✅ Skip jika form tidak ada

            form.addEventListener('submit', function(e) {
                const btn = document.getElementById('submitBtn');
                if (!btn) return;

                if (btn.disabled) {
                    e.preventDefault();
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Loading...';
            });
        });
    </script>

@if(session('success') || session('error') || session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');

        const toastConfig = {
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timerProgressBar: true,
            background: isDark ? '#1f2937' : '#ffffff',
            color: isDark ? '#f3f4f6' : '#111827',
            customClass: {
                popup: isDark ? 'swal-dark-toast' : 'swal-light-toast',
                timerProgressBar: isDark ? 'swal-dark-progress' : '',
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        };

        @if(session('success'))
        Swal.fire({
            ...toastConfig,
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
        });
        @endif

        @if(session('error'))
        Swal.fire({
            ...toastConfig,
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 4000,
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            ...toastConfig,
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}',
            timer: 4000,
        });
        @endif
    });
</script>
@endif
</body>


</html>
