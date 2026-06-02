<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-300 font-sans antialiased" x-data>
    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')

        <!-- Page Heading -->
        {{-- @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif --}}

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Topbar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 hover:underline">Logout</button>
                    </form>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if (session('success') || session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        @if (session('success'))
                            flashMessage('success', '{{ session('success') }}');
                        @endif
                        @if (session('error'))
                            flashMessage('error', '{{ session('error') }}');
                        @endif
                    });
                </script>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
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
