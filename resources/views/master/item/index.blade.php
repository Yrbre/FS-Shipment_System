@extends('layouts.app')
@section('title', 'Item')
@section('content')
<div class="">
    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 mb-4">
        <h3 class="dark:text-gray-200">Item</h3>
        @can('master.item.create')
        <a href="{{ route('master.items.create') }}" class="flex items-center gap-1 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
           <i class="fas fa-plus"></i> Tambah Item</a>
        @endcan
    </div>
@endsection
