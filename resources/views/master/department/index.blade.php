@extends('layouts.app')
@section('title', 'Department')
@section('menu-active', 'active')
@section('active-departments', 'active')
@section('content')
    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 mb-4">
        <h3 class="dark:text-gray-200">Department</h3>
        @can('master.department.create')
            <a href="{{ route('master.departments.create') }}"
                class="flex items-center gap-1 px-4 py-3 text-sm font-medium text-black transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                <i class="fas fa-plus"></i> Tambah Department</a>
        @endcan
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                Department List
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $department->code }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    @can('master.department.edit')
                                        <a href="{{ route('master.departments.edit', $department->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>Edit
                                        </a>
                                    @endcan
                                    @can('master.department.delete')
                                        <a class="btn btn-sm btn-danger js-delete-dept" data-name="{{ $department->name }}"
                                            data-code="{{ $department->code }}"
                                            data-url="{{ route('master.departments.destroy', $department->id) }}">
                                            <i class="fas fa-trash"></i>Hapus
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

    </section>
    @push('script')
        <script>
            // Simple Datatable
            let table1 = document.querySelector('#table1');
            let dataTable = new simpleDatatables.DataTable(table1);
        </script>

        <script>
            $(document).on('click', '.js-delete-dept', function(e) {
                e.preventDefault();

                var button = $(this);
                var taskName = button.data('name');
                var taskCode = button.data('code');
                var deleteUrl = button.data('url');

                Swal.fire({
                    title: 'Confirm Delete',
                    icon: 'warning',
                    theme: 'dark',
                    html: '<p>Are you sure you want to delete this Absence?</p>' +
                        '<div class="justify-content-center">' +
                        '<strong>Name :</strong> ' + taskName + '<br>' +
                        '<strong>Code :</strong> ' + taskCode + '<br>' +
                        '</div>' +
                        '<p class="mt-3 mb-0 text-muted">This action cannot be undone.</p>',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $('#deleteForm');
                        form.attr('action', deleteUrl);
                        form.trigger('submit');
                    }
                });
            });
        </script>
    @endpush

@endsection
