@extends('layouts.app')
@section('title', 'Shipment')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h2 class="page-title">
                    <i class="fa-solid fa-plane-arrival" style="color:#3753ce"></i> Shipment
                </h2>
                @can('shipment.create')
                    <a href="{{ route('shipments.create') }}" class="btn btn-primary">Add Shipment</a>
                @endcan
            </div>

            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatables" id="dataTable-1" style="width:100%">
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
                            </div>

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
@endsection

@push('scripts')
<script>
    // Handle delete
    $(document).on('click', '.js-delete', function(e) {
        e.preventDefault();
        var button = $(this);
        var name   = button.data('name');
        var role   = button.data('role');
        var url    = button.data('url');

        Swal.fire({
            title: 'Confirm Delete',
            icon: 'warning',
            theme: 'dark',
            html: '<p>Are you sure you want to delete this shipment?</p>' +
                  '<strong>PO :</strong> ' + name,
            showCancelButton:   true,
            confirmButtonText:  'Yes, Delete',
            cancelButtonText:   'Cancel',
            confirmButtonColor: '#dc3545',
            cancelButtonColor:  '#6c757d',
            didClose: () => {
                document.body.classList.remove('swal2-shown');
                document.body.style.overflow    = '';
                document.body.style.paddingRight = '';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', url);
                form.submit();
            }
        });
    });
</script>

<script>
    $('#dataTable-1').DataTable({
        autoWidth:   false,   // ← matikan autoWidth
        processing:  true,
        serverSide:  true,
        responsive:  true,    // ← aktifkan responsive
        ajax: '{{ route('shipments.index') }}',
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable:  false,
                searchable: false,
                width: '40px',
            },
            { data: 'po',         name: 'po' },
            { data: 'supplier',   name: 'supplier' },
            { data: 'no_invoice', name: 'no_invoice' },
            { data: 'no_bl',      name: 'no_bl' },
            {
                data: 'etd',
                name: 'etd',
                width: '90px',
            },
            {
                data: 'eta',
                name: 'eta',
                width: '90px',
            },
            {
                data: 'status',
                name: 'status',
                width: '90px',
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                width: '130px',
            },
            {
                data:       'action',
                name:       'action',
                orderable:  false,
                searchable: false,
                width:      '120px',
            },
        ],
        lengthMenu: [
            [16, 32, 64, -1],
            [16, 32, 64, 'All']
        ],
        language: {
            processing: '<i class="fe fe-loader fe-spin"></i> Loading...',
        },
        columnDefs: [
            // Kolom yang bisa disembunyikan di mobile
            { responsivePriority: 1, targets: 1 },  // PO — selalu tampil
            { responsivePriority: 2, targets: 9 },  // Action — selalu tampil
            { responsivePriority: 3, targets: 7 },  // Status
            { responsivePriority: 4, targets: 2 },  // Supplier
            { responsivePriority: 5, targets: 6 },  // ETA
            { responsivePriority: 6, targets: 5 },  // ETD
            { responsivePriority: 7, targets: 3 },  // No Invoice
            { responsivePriority: 8, targets: 4 },  // No B/L
            { responsivePriority: 9, targets: 8 },  // Updated At — hide duluan di mobile
        ],
    });
</script>
@endpush
