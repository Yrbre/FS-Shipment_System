@extends('layouts.app')
@section('title', 'Tambah Department')

@section('content')

{{-- Breadcrumb --}}
<nav class="mb-5 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
    <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition-colors">
        <i class="fa-solid fa-house text-xs"></i>
    </a>
    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 dark:text-gray-600"></i>
    <a href="{{ route('master.departments.index') }}" class="hover:text-brand-500 transition-colors">Department</a>
    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 dark:text-gray-600"></i>
    <span class="text-gray-800 dark:text-white font-medium">Edit Department</span>
</nav>

{{-- Layout 2 kolom: form kiri, info kanan --}}
<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

    {{-- Kolom Kiri — Form Utama (2/3) --}}
    <div class="xl:col-span-2">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 shadow-sm">

            {{-- Header --}}
            <div class="flex items-center gap-4 border-b border-gray-100 dark:border-gray-800 px-6 py-5">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10 flex-shrink-0">
                    <i class="fa-solid fa-building text-brand-500 dark:text-brand-400 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Edit Department</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Lengkapi informasi department di bawah ini</p>
                </div>
            </div>

            {{-- Body Form --}}
            <form action="{{ route('master.departments.update', $department->id) }}" method="POST" id="myForm">
                @csrf
                @method('PUT')

                <div class="px-6 py-6 space-y-6">

                    {{-- Kode Department --}}
                    <div>
                        <label for="code"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Department
                            <span class="text-error-500 ml-0.5">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <span class="text-gray-400 dark:text-gray-500 text-sm font-mono">#</span>
                            </div>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code', $department->code) }}"
                                placeholder="Contoh: IT, HR, FIN, PROD"
                                maxlength="20"
                                autocomplete="off"
                                class="h-11 w-full rounded-xl border border-gray-300 pl-8 pr-4 py-2.5 text-sm font-mono text-gray-800 dark:text-white/90 placeholder:text-gray-400 dark:placeholder:text-white/20 dark:bg-gray-900 shadow-theme-xs focus:outline-none focus:ring-3 transition-all duration-150 uppercase
                                    {{ $errors->has('code')
                                        ? 'border-error-500 focus:border-error-400 focus:ring-error-500/10'
                                        : 'border-gray-200 dark:border-gray-700 focus:border-brand-400 focus:ring-brand-500/10 dark:focus:border-brand-700' }}"
                            />
                        </div>
                        @error('code')
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-error-500">
                                <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama Department --}}
                    <div>
                        <label for="name"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Department
                            <span class="text-error-500 ml-0.5">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <i class="fa-solid fa-id-card text-gray-400 dark:text-gray-500 text-sm"></i>
                            </div>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $department->name) }}"
                                placeholder="Contoh: Information Technology"
                                maxlength="100"
                                autocomplete="off"
                                class="h-11 w-full rounded-xl border border-gray-300 pl-9 pr-4 py-2.5 text-sm text-gray-800 dark:text-white/90 placeholder:text-gray-400 dark:placeholder:text-white/20 dark:bg-gray-900 shadow-theme-xs focus:outline-none focus:ring-3 transition-all duration-150
                                    {{ $errors->has('name')
                                        ? 'border-error-500 focus:border-error-400 focus:ring-error-500/10'
                                        : 'border-gray-200 dark:border-gray-700 focus:border-brand-400 focus:ring-brand-500/10 dark:focus:border-brand-700' }}"
                            />
                        </div>
                        @error('name')
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-error-500">
                                <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end border-t border-gray-100 dark:border-gray-800 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('master.departments.index') }}"
                            class="flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-gray-700 shadow-theme-xs transition-all duration-150">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            Batal
                        </a>
                        <button
                            type="submit"
                            id="submitBtn"
                            class="flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-150">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            Update Department
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Kolom Kanan — Panduan & Info (1/3) --}}
    <div class="xl:col-span-1 flex flex-col gap-5">

        {{-- Info Box --}}
        <div class="rounded-2xl border border-blue-100 dark:border-blue-500/20 bg-blue-50 dark:bg-blue-500/10 p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-500/20 flex-shrink-0">
                    <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 text-sm"></i>
                </div>
                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300">Informasi</h4>
            </div>
            <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                Department yang diedit akan dapat di-assign ke pengguna dengan role <strong class="font-semibold">User</strong> dan digunakan saat membuat shipment order.
            </p>
        </div>

        {{-- Panduan Pengisian --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-book-open text-gray-400 dark:text-gray-500 text-xs"></i>
                Panduan Pengisian
            </h4>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <div class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10 mt-0.5">
                        <span class="text-[10px] font-bold text-brand-500">1</span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Kode Department</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Singkatan unik, huruf kapital. Contoh: <code class="font-mono bg-gray-100 dark:bg-gray-800 px-1 rounded">IT</code></p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10 mt-0.5">
                        <span class="text-[10px] font-bold text-brand-500">2</span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Nama Department</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Nama lengkap departemen. Contoh: <code class="font-mono bg-gray-100 dark:bg-gray-800 px-1 rounded">Information Technology</code></p>
                    </div>
                </li>
            </ul>
        </div>

        {{-- Tips --}}
        <div class="rounded-2xl border border-amber-100 dark:border-amber-500/20 bg-amber-50 dark:bg-amber-500/10 p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-500/20 flex-shrink-0">
                    <i class="fa-solid fa-lightbulb text-amber-500 dark:text-amber-400 text-sm"></i>
                </div>
                <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-300">Tips</h4>
            </div>
            <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                Kode department tidak dapat diubah setelah digunakan di shipment. Pastikan kode sudah sesuai sebelum menyimpan.
            </p>
        </div>

    </div>

</div>

@endsection
