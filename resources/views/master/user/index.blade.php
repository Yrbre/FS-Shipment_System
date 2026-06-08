@extends('layouts.app')
@section('title', 'User')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-list" style="color:#3753ce "></i> User Master</h2>
                @can('master.user.create')
                    <a href="{{ route('master.users.create') }}" class="btn btn-primary">Add User</a>
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
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>dibuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
    </div>
    @push('scripts')
        <script>
            // Handle delete modal
            $(document).on('click', '.js-delete', function(e) {
                e.preventDefault();
                var button = $(this);
                var name = button.data('name');
                var role = button.data('role');
                var url = button.data('url');

                Swal.fire({
                    title: 'Confirm Delete',
                    icon: 'warning',
                    theme: 'dark',
                    html: '<p>Are you sure you want to delete this User?</p>' +
                        '<div class="justify-content-center">' +
                        '<strong>User Name :</strong> ' + name + '<br>' +
                        '<strong>Role :</strong> ' + role +
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


        {{-- Data Table --}}
        <script>
            $('#dataTable-1').DataTable({
                autoWidth: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('master.users.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                lengthMenu: [
                    [16, 32, 64, -1],
                    [16, 32, 64, 'All']
                ]
            });
        </script>
    @endpush
@endsection
