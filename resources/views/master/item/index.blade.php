{{-- resources/views/master/items/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Item')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-semibold text-gray-700">Daftar Item</h2>
            @can('master.item.create')
                <a href="{{ route('master.items.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    + Tambah Item
                </a>
            @endcan
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Uom</th>
                    <th class="px-4 py-3 text-left">Deskripsi</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono">{{ $item->code }}</td>
                        <td class="px-4 py-3">{{ $item->name }}</td>
                        <td class="px-4 py-3">{{ $item->uom }}</td>
                        <td class="px-4 py-3">{{ $item->description }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            @can('master.item.edit')
                                <a href="{{ route('master.items.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                            @endcan
                            @can('master.item.delete')
                                <form id="del-{{ $item->id }}" method="POST"
                                    action="{{ route('master.items.destroy', $item) }}">
                                    @csrf @method('DELETE')
                                </form>
                                <button onclick="confirmDelete('del-{{ $item->id }}')"
                                    class="text-red-500 hover:underline">Hapus</button>
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
