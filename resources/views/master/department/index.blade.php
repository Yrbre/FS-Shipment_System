@extends('layouts.app')
@section('title', 'Department')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6 ">
            <h2 class="font-semibold text-gray-700">Daftar Department</h2>
            @can('master.department.create')
                <a href="{{ route('master.departments.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    + Tambah Department
                </a>
            @endcan
        </div>

        <table class="table w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($departments as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono">{{ $item->code }}</td>
                        <td class="px-4 py-3">{{ $item->name }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            @can('master.department.edit')
                                <a href="{{ route('master.departments.edit', $item) }}"
                                    class="btn btn-outline-primary text-blue-600 ">Edit</a>
                            @endcan
                            @can('master.department.delete')
                                <form id="del-{{ $item->id }}" method="POST"
                                    action="{{ route('master.departments.destroy', $item) }}">
                                    @csrf @method('DELETE')
                                </form>
                                <button onclick="confirmDelete('del-{{ $item->id }}')"
                                    class="btn btn-outline-danger ">Hapus</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
