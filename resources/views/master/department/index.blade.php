@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-building" style="color:#3753ce "></i> Department Master</h2>
                @can('master.department.create')
                <a href="{{ route('master.departments.create') }}" class="btn btn-primary">Add Department</a>
                @endcan
            </div>
            <div class="row my-4">
                <!-- Small table -->
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <!-- table -->
                            <table class="table datatables" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Dibuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->code}}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('master.departments.edit', $item->id) }}">Edit</a>
                                                    <a class="dropdown-item js-delete" data-id="{{ $item->id }}"
                                                        data-name="{{ $item->name }}"
                                                        data-code="{{ $item->code }}"
                                                        data-url={{ route('master.departments.destroy', $item->id) }}
                                                        href="#">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <form id="deleteForm" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div> <!-- simple table -->
            </div> <!-- end section -->
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
    {{-- DataTableScript --}}
    @push('scripts')
{{-- Modal Delete --}}
    <script>
        // Handle delete modal
        $(document).on('click', '.js-delete', function(e) {
            e.preventDefault();
            var button = $(this);
            var name = button.data('name');
            var code = button.data('code');
            var url = button.data('url');

            Swal.fire({
                title: 'Confirm Delete',
                icon: 'warning',
                theme: 'dark',
                html: '<p>Are you sure you want to delete this Department?</p>' +
                    '<div class="justify-content-center">' +
                    '<strong>Department Name :</strong> ' + name + '<br>' +
                    '<strong>Code :</strong> ' + code +
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
                lengthMenu: [
                    [16, 32, 64, -1],
                    [16, 32, 64, 'All']
                ]
            });
        </script>
    @endpush
@endsection
