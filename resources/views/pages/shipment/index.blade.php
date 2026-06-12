@extends('layouts.app')
@section('title', 'Shipment')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="page-title"> <i class="fa-solid fa-plane-arrival" style="color:#3753ce "></i> Shipment</h2>
                @can('shipment.create')
                    <a href="{{ route('shipments.create') }}" class="btn btn-primary">Add Shipment</a>
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
                                        <th>Purchase Order</th>
                                        <th>Supplier</th>
                                        <th>No Invoice</th>
                                        <th>No B/L</th>
                                        <th>ETD</th>
                                        <th>ETA</th>
                                        <th>Status</th>
                                        <th>Updated At</th>
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
                ajax: '{{ route('shipments.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'no_invoice',
                        name: 'no_invoice'
                    },
                    {
                        data: 'no_bl',
                        name: 'no_bl'
                    },
                    {
                        data: 'etd',
                        name: 'etd'
                    },
                    {
                        data: 'eta',
                        name: 'eta'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
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
