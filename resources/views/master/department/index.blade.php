@extends('layouts.app')
@section('title', 'Department')
@section('content')
<div class="">
    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 mb-4">
        <h3 class="dark:text-gray-200">Department</h3>
        @can('master.department.create')
        <a href="{{ route('master.departments.create') }}" class="flex items-center gap-1 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.0002 4.875C12.6216 4.875 13.1252 5.37868 13.1252 6V10.8752H18.0007C18.622 10.8752 19.1257 11.3789 19.1257 12.0002C19.1257 12.6216 18.622 13.1252 18.0007 13.1252H13.1252V18.0007C13.1252 18.622 12.6216 19.1257 12.0002 19.1257C11.3789 19.1257 10.8752 18.622 10.8752 18.0007V13.1252H6C5.37868 13.1252 4.875 12.6216 4.875 12.0002C4.875 11.3789 5.37868 10.8752 6 10.8752H10.8752V6C10.8752 5.37868 11.3789 4.875 12.0002 4.875Z" fill="#323544"/>
</svg>

            Tambah Department</a>
        @endcan
    </div>

        <livewire:tables.department-table />

</div>
@endsection
