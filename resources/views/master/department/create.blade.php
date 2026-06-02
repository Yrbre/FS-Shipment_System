@extends('layouts.app')
@section('title', 'Create Department')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="font-semibold text-gray-700">Tambah Department</div>
        </div>

        <form action="{{ route('master.departments.store') }}" method="POST" id="myForm">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input type="text" name="code"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500  {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Kode department" value="{{ old('code') }}">
                    @error('code')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input type="text" name="name"
                        class=" w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Nama department" value="{{ old('name') }}">
                    @error('name')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex row gap-4 mt-4 justify-end">
                <a href="{{ route('master.departments.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitBtn"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
