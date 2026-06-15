@extends('layouts.app')
@section('title', 'Role Master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-list" style="color:#3753ce "></i> Role Master</h2>
                @can('master.role.create')
                    <a href="{{ route('master.roles.create') }}" class="btn btn-primary">Add Role</a>
                @endcan
            </div>
            <div class="row my-4">

                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">

                            <table class="table datatables" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Role Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roles as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @can('master.role.edit')
                                                    <a href="{{ route('master.roles.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                @endcan

                                                @can('master.role.delete')
                                                    <button type="button" class="btn btn-sm btn-danger js-delete"
                                                        data-name="{{ $item->name }}"
                                                        data-url="{{ route('master.roles.destroy', $item->id) }}">Delete</button>
                                                @endcan

                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <form id="deleteForm" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // Handle delete modal
            $(document).on('click', '.js-delete', function(e) {
                e.preventDefault();
                var button = $(this);
                var name = button.data('name');
                var url = button.data('url');

                Swal.fire({
                    title: 'Confirm Delete',
                    icon: 'warning',
                    theme: 'dark',
                    html: '<p>Are you sure you want to delete this Item?</p>' +
                        '<div class="justify-content-center">' +
                        '<strong>Item Name :</strong> ' + name + '<br>' +
                        '</div>',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $('#deleteForm');
                        form.attr('action', url);
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
