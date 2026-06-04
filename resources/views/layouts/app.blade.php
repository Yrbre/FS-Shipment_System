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

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
</script>
@endif
</body>


</html>
