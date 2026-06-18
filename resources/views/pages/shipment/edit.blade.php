@extends('layouts.app')

@section('title', 'Edit Shipment')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">

            {{-- Breadcrumb & Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span>
                    <a href="{{ route('shipments.index') }}" class="text-secondary text-decoration-none">Shipment</a>
                    <span class="text-secondary mx-1">/</span>
                    <a href="{{ route('shipments.show', $shipment->id) }}" class="text-secondary text-decoration-none">
                        {{ $shipment->po }}
                    </a>
                    <span class="text-secondary mx-1">/</span>
                    <span class="text-primary">Edit</span>
                </span>
                <span class="badge bg-info text-white px-3 py-2">
                    {{ $shipment->status->name ?? '-' }}
                </span>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <strong>Terdapat kesalahan pada input:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('shipments.update', $shipment->id) }}" method="POST" id="shipment-form">
                @csrf
                @method('PUT')

                {{-- Section 1: Shipment Information --}}
                <section class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-info text-primary me-1"></i>
                            <h5 class="mb-0 text-white">Shipment Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="po" class="form-label text-white">
                                        Purchase Order <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('po') is-invalid @enderror"
                                        id="po" name="po" value="{{ old('po', $shipment->po) }}"
                                        placeholder="e.g. PO-2024-001">
                                    @error('po')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="no_invoice" class="form-label text-white">
                                        No Invoice <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('no_invoice') is-invalid @enderror"
                                        id="no_invoice" name="no_invoice"
                                        value="{{ old('no_invoice', $shipment->no_invoice) }}"
                                        placeholder="e.g. INV-2024-001">
                                    @error('no_invoice')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="no_bl" class="form-label text-white">
                                        No B/L <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('no_bl') is-invalid @enderror"
                                        id="no_bl" name="no_bl" value="{{ old('no_bl', $shipment->no_bl) }}"
                                        placeholder="e.g. BL-2024-001">
                                    @error('no_bl')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="supplier_id" class="form-label text-white">
                                        Supplier <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2-supplier @error('supplier_id') is-invalid @enderror"
                                        id="supplier_id" name="supplier_id">
                                        <optgroup label="Select Supplier">
                                            <option value="" disabled>Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id', $shipment->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                            <option value="other" {{ old('supplier_id') === 'other' ? 'selected' : '' }}>
                                                ➕ Other (New Supplier)
                                            </option>
                                        </optgroup>
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <input type="text"
                                        class="form-control mt-2 uppercase @error('new_supplier_name') is-invalid @enderror"
                                        id="new_supplier_name" name="new_supplier_name"
                                        value="{{ old('new_supplier_name') }}" placeholder="Nama supplier baru..."
                                        {{ old('supplier_id') === 'other' ? '' : 'style=display:none' }}>
                                    @error('new_supplier_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="department_id" class="form-label text-white">
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        class="form-control select2-department @error('department_id') is-invalid @enderror"
                                        id="department_id" name="department_id">
                                        <optgroup label="Select Department">
                                            <option value="" disabled>Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ old('department_id', $shipment->department_id) == $department->id ? 'selected' : '' }}>
                                                    [{{ $department->code }}] {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="etd" class="form-label text-white">
                                        ETD <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('etd') is-invalid @enderror"
                                        id="etd" name="etd"
                                        value="{{ old('etd', $shipment->etd?->format('Y-m-d')) }}">
                                    @error('etd')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="eta" class="form-label text-white">
                                        ETA <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('eta') is-invalid @enderror"
                                        id="eta" name="eta"
                                        value="{{ old('eta', $shipment->eta?->format('Y-m-d')) }}">
                                    @error('eta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="notes" class="form-label text-white">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2"
                                        placeholder="Additional notes...">{{ old('notes', $shipment->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section 2: Update Status --}}
                @can('shipment.edit')
                    <section class="mb-4">
                        <div class="card shadow-sm border-warning" style="border-left: 3px solid #ffc107 !important;">
                            <div class="card-header d-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle-nodes text-warning"></i>
                                <h5 class="mb-0 text-white">Update Status</h5>
                                <span class="ms-auto badge bg-info text-white">
                                    Current: {{ $shipment->status->name ?? '-' }}
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-5">
                                        <label for="status_id" class="form-label text-white">Status Baru</label>
                                        <select class="form-control select2-status @error('status_id') is-invalid @enderror"
                                            id="status_id" name="status_id">
                                            <optgroup label="Select Status">
                                                <option value="" disabled>-- Pilih Status --</option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->id }}"
                                                        {{ old('status_id', $shipment->status_id) == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        @error('status_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-7">
                                        <label for="status_notes" class="form-label text-white">
                                            Catatan Perubahan Status
                                            <span class="text-muted small">(opsional)</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('status_notes') is-invalid @enderror"
                                            id="status_notes" name="status_notes" value="{{ old('status_notes') }}"
                                            placeholder="e.g. Barang sudah berangkat dari pelabuhan...">
                                        @error('status_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if ($statuses->count() > 0)
                                    <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                                        @foreach ($statuses as $status)
                                            <div class="d-flex align-items-center gap-1">
                                                <span
                                                    class="badge {{ $shipment->status_id == $status->id ? 'bg-primary' : 'bg-secondary opacity-50' }}">
                                                    {{ $status->name }}
                                                </span>
                                                @if (!$loop->last)
                                                    <i class="fa-solid fa-arrow-right text-muted" style="font-size:10px;"></i>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                @endcan

                {{-- Section 3: Items --}}
                <section class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-boxes-stacked text-primary"></i>
                                <h5 class="mb-0 text-white">Items</h5>
                            </div>
                            <span class="badge bg-info text-white" id="item-count-badge">
                                {{ $shipment->items->count() }} item{{ $shipment->items->count() > 1 ? 's' : '' }}
                            </span>
                        </div>
                        <div class="card-body p-0">

                            @if ($errors->hasAny(['rf.*', 'item_name.*', 'hscode.*', 'quantity.*', 'uom.*']))
                                <div class="alert alert-danger mb-0 rounded-0 border-0 border-bottom">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                                    Terdapat kesalahan pada item — mohon periksa kembali setiap baris.
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table mb-0" id="items-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width:44px;" class="text-center">#</th>
                                            <th style="width:25%;">Item</th>
                                            <th style="width:12%;">HS Code</th>
                                            <th style="width:16%;">New Item Name</th>
                                            <th style="width:9%;">Qty</th>
                                            <th style="width:13%;">UOM</th>
                                            <th>Notes</th>
                                            <th style="width:52px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-body">

                                        @if (old('rf'))
                                            {{-- Restore rows on validation fail --}}
                                            @foreach (old('rf') as $index => $oldRf)
                                                @php
                                                    $oldItemName = old('item_name.' . $index, '');
                                                    $oldHscode = old('hscode.' . $index, '');
                                                    $oldNewName = old('new_item_name.' . $index, '');
                                                    $isOther = empty($oldRf);
                                                @endphp
                                                <tr data-selected-rf="{{ $oldRf }}"
                                                    data-selected-text="{{ $oldItemName ? '[' . $oldRf . '] ' . $oldItemName : '' }}"
                                                    data-selected-hscode="{{ $oldHscode }}">

                                                    <td class="text-center text-muted row-num">{{ $index + 1 }}</td>
                                                    <td>
                                                        <select
                                                            class="form-control form-control-sm select2-item @error('rf.' . $index) is-invalid @enderror"
                                                            name="rf_select[]">
                                                            @if (!$isOther && $oldRf)
                                                                <option value="{{ $oldRf }}" selected>
                                                                    [{{ $oldRf }}] {{ $oldItemName }}
                                                                </option>
                                                            @endif
                                                            <option value="other" {{ $isOther ? 'selected' : '' }}>
                                                                ➕ Other (New Item)
                                                            </option>
                                                        </select>
                                                        <input type="hidden" name="rf[]"
                                                            value="{{ $oldRf }}">
                                                        <input type="hidden" name="item_name[]"
                                                            value="{{ $oldItemName }}">
                                                        @error('rf.' . $index)
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm item-hscode"
                                                            name="hscode[]" value="{{ $oldHscode }}"
                                                            placeholder="HS Code" readonly
                                                            style="{{ $oldHscode ? '' : 'opacity:.4; background:transparent;' }}">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm new-item-name @error('new_item_name.' . $index) is-invalid @enderror"
                                                            name="new_item_name[]" value="{{ $oldNewName }}"
                                                            placeholder="Item name..." {{ $isOther ? '' : 'readonly' }}
                                                            style="{{ $isOther ? '' : 'opacity:.4; background:transparent;' }}">
                                                        @error('new_item_name.' . $index)
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            class="form-control form-control-sm @error('quantity.' . $index) is-invalid @enderror"
                                                            name="quantity[]" value="{{ old('quantity.' . $index) }}"
                                                            placeholder="0" min="0" step="0.01">
                                                        @error('quantity.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm item-uom uppercase @error('uom.' . $index) is-invalid @enderror"
                                                            name="uom[]" value="{{ old('uom.' . $index) }}"
                                                            placeholder="e.g. PCS">
                                                        @error('uom.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="item_notes[]" value="{{ old('item_notes.' . $index) }}"
                                                            placeholder="Notes...">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger btn-delete-row"
                                                            title="Remove item">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            {{-- Load dari data shipment existing --}}
                                            @foreach ($shipment->items as $si)
                                                <tr data-selected-rf="{{ $si->rf }}"
                                                    data-selected-text="{{ $si->rf ? '[' . $si->rf . '] ' . $si->item->name : '' }}"
                                                    data-selected-hscode="{{ $si->hscode }}">

                                                    <td class="text-center text-muted row-num">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <select class="form-control form-control-sm select2-item"
                                                            name="rf_select[]">
                                                            @if ($si->rf)
                                                                <option value="{{ $si->rf }}" selected>
                                                                    [{{ $si->rf }}] {{ $si->item->name ?? '' }}
                                                                </option>
                                                            @endif
                                                            <option value="other">➕ Other (New Item)</option>
                                                        </select>
                                                        <input type="hidden" name="rf[]"
                                                            value="{{ $si->rf }}">
                                                        <input type="hidden" name="item_name[]"
                                                            value="{{ $si->item->name ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm item-hscode"
                                                            name="hscode[]" value="{{ $si->hscode }}"
                                                            placeholder="HS Code" readonly
                                                            style="{{ $si->hscode ? '' : 'opacity:.4; background:transparent;' }}">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm new-item-name"
                                                            name="new_item_name[]" value=""
                                                            placeholder="Item name..." readonly
                                                            style="opacity:.4; background:transparent;">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm"
                                                            name="quantity[]" value="{{ $si->quantity + 0 }}"
                                                            placeholder="0" min="0" step="0.01">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm item-uom uppercase"
                                                            name="uom[]" value="{{ $si->uom }}"
                                                            placeholder="e.g. PCS">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="item_notes[]" value="{{ $si->notes }}"
                                                            placeholder="Notes...">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger btn-delete-row"
                                                            title="Remove item">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>

                            <div class="p-3 border-top">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-add-row">
                                    <i class="fa-solid fa-plus me-1"></i> Add Item
                                </button>
                            </div>

                        </div>
                    </div>
                </section>

                {{-- Actions --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <small class="text-muted">
                        <i class="fa-solid fa-clock me-1"></i>
                        Last updated: {{ $shipment->updated_at->format('d M Y, H:i') }}
                    </small>
                    <div class="d-flex gap-2">
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        /* ── Kunci lebar kolom tabel agar stabil apapun isinya ── */
        #items-table {
            table-layout: fixed;
            width: 100%;
        }

        /* Cegah isi sel mendorong lebar kolom */
        #items-table td,
        #items-table th {
            overflow: hidden;
        }

        /* ── Select2 Item mengisi penuh kolom, tanpa gap ──────── */
        #items-table .select2-item+.select2-container {
            width: 100% !important;
            max-width: 100%;
        }

        /* Teks panjang dipotong rapi dengan ellipsis */
        #items-table .select2-item+.select2-container .select2-selection__rendered {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }

        /* Dropdown saat memilih tetap lebar agar teks lengkap terbaca */
        .select2-item-dropdown {
            width: auto !important;
            min-width: 300px;
            max-width: 600px;
        }

        .select2-item-dropdown .select2-results__option {
            white-space: nowrap;
            overflow: visible;
            text-overflow: unset;
        }

        /* ── Mobile: beri lebar minimum + scroll horizontal ──────── */
        @media (max-width: 768px) {

            /* Wrapper bawaan Bootstrap, aktifkan scroll samping */
            .table-responsive {
                -webkit-overflow-scrolling: touch;
            }

            /* Lepas penguncian persen, pakai lebar tetap (px) agar terbaca */
            #items-table {
                table-layout: auto;
                min-width: 900px;
                /* total lebar tabel di mobile, sesuaikan */
            }

            /* Lebar kolom dalam px supaya tiap kolom tetap nyaman dibaca */
            #items-table th:nth-child(1),
            #items-table td:nth-child(1) {
                width: 40px;
            }

            /* # */
            #items-table th:nth-child(2),
            #items-table td:nth-child(2) {
                width: 240px;
            }

            /* Item */
            #items-table th:nth-child(3),
            #items-table td:nth-child(3) {
                width: 110px;
            }

            /* HS Code */
            #items-table th:nth-child(4),
            #items-table td:nth-child(4) {
                width: 150px;
            }

            /* New Item Name */
            #items-table th:nth-child(5),
            #items-table td:nth-child(5) {
                width: 80px;
            }

            /* Qty */
            #items-table th:nth-child(6),
            #items-table td:nth-child(6) {
                width: 110px;
            }

            /* UOM */
            #items-table th:nth-child(7),
            #items-table td:nth-child(7) {
                width: 150px;
            }

            /* Notes */
            #items-table th:nth-child(8),
            #items-table td:nth-child(8) {
                width: 50px;
            }

            /* Hapus */

            /* Pastikan select2 tetap mengisi penuh sel-nya di mobile */
            #items-table .select2-item+.select2-container {
                width: 100% !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.getElementById('shipment-form').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');

            if (btn.disabled) {
                e.preventDefault(); // cegah submit kedua
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Loading...';
        });
    </script>
    <script>
        (function() {

            const tbody = document.getElementById('items-body');
            const addRowBtn = document.getElementById('btn-add-row');
            const badge = document.getElementById('item-count-badge');
            const API_URL = 'http://vapps/hscode/api/data';

            // ── Init Select2 AJAX ─────────────────────────────────────────
            function initSelect2Item(el, selectedRf, selectedText) {
                const $el = $(el);

                if (selectedRf && selectedRf !== 'other') {
                    if ($el.find('option[value="' + selectedRf + '"]').length === 0) {
                        $el.prepend(new Option(selectedText, selectedRf, true, true));
                    }
                }

                $el.select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: '-- Ketik untuk mencari item --',
                    dropdownAutoWidth: true,
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                        url: API_URL,
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                search: params.term,
                                limit: 20
                            };
                        },
                        processResults: function(response) {
                            if (response.status !== 'success') return {
                                results: []
                            };

                            const results = response.data.map(function(d) {
                                return {
                                    id: d.rf,
                                    text: '[' + d.rf + '] ' + d.item,
                                    rf: d.rf,
                                    item_name: d.item,
                                    hscode: d.hscode,
                                };
                            });

                            results.push({
                                id: 'other',
                                text: '➕ Other (New Item)',
                                rf: '',
                                item_name: '',
                                hscode: ''
                            });

                            return {
                                results: results
                            };
                        },
                        cache: true,
                    },
                });
            }

            // ── Handle pilihan item ───────────────────────────────────────
            function bindOtherToggle(row) {
                const sel = row.querySelector('.select2-item');
                const nameInput = row.querySelector('.new-item-name');
                const uomInput = row.querySelector('.item-uom');
                const hsInput = row.querySelector('.item-hscode');
                const rfInput = row.querySelector('input[name="rf[]"]');
                const itemNameInput = row.querySelector('input[name="item_name[]"]');

                $(sel).on('select2:select', function(e) {
                    const data = e.params.data;

                    if (data.id === 'other') {
                        if (rfInput) rfInput.value = '';
                        if (itemNameInput) itemNameInput.value = '';

                        nameInput.readOnly = false;
                        nameInput.style.opacity = '1';
                        nameInput.style.background = '';
                        uomInput.value = '';
                        uomInput.readOnly = false;

                        if (hsInput) {
                            hsInput.value = '';
                            hsInput.style.opacity = '.4';
                            hsInput.style.background = 'transparent';
                        }

                        setTimeout(() => nameInput.focus(), 50);

                    } else {
                        if (rfInput) rfInput.value = data.rf || '';
                        if (itemNameInput) itemNameInput.value = data.item_name || '';

                        nameInput.readOnly = true;
                        nameInput.style.opacity = '.4';
                        nameInput.style.background = 'transparent';
                        nameInput.value = '';
                        uomInput.readOnly = false;

                        if (hsInput) {
                            hsInput.value = data.hscode || '';
                            hsInput.style.opacity = data.hscode ? '1' : '.4';
                            hsInput.style.background = data.hscode ? '' : 'transparent';
                        }
                    }
                });

                $(sel).on('select2:clear', function() {
                    if (rfInput) rfInput.value = '';
                    if (itemNameInput) itemNameInput.value = '';

                    nameInput.readOnly = true;
                    nameInput.style.opacity = '.4';
                    nameInput.style.background = 'transparent';
                    nameInput.value = '';

                    if (hsInput) {
                        hsInput.value = '';
                        hsInput.style.opacity = '.4';
                        hsInput.style.background = 'transparent';
                    }
                });
            }

            // ── Build row baru ────────────────────────────────────────────
            function buildRow(num) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td class="text-center text-muted row-num">${num}</td>
            <td>
                <select class="form-control form-control-sm select2-item" name="rf_select[]"></select>
                <input type="hidden" name="rf[]"        value="">
                <input type="hidden" name="item_name[]" value="">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm item-hscode"
                    name="hscode[]" placeholder="HS Code" readonly
                    style="opacity:.4; background:transparent;">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm new-item-name"
                    name="new_item_name[]" placeholder="Item name..." readonly
                    style="opacity:.4; background:transparent;">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm"
                    name="quantity[]" placeholder="0" min="0" step="0.01">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm item-uom uppercase"
                    name="uom[]" placeholder="e.g. PCS">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm"
                    name="item_notes[]" placeholder="Notes...">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-row" title="Remove item">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
                return tr;
            }

            // ── Renumber + badge ──────────────────────────────────────────
            function refresh() {
                const rows = tbody.querySelectorAll('tr');
                rows.forEach((tr, i) => {
                    const cell = tr.querySelector('.row-num');
                    if (cell) cell.textContent = i + 1;
                });
                const n = rows.length;
                badge.textContent = n + (n === 1 ? ' item' : ' items');
            }

            // ── Init existing rows ────────────────────────────────────────
            tbody.querySelectorAll('tr').forEach(row => {
                const sel = row.querySelector('.select2-item');
                const selectedRf = row.dataset.selectedRf || '';
                const selectedTx = row.dataset.selectedText || '';
                if (sel) {
                    initSelect2Item(sel, selectedRf, selectedTx);
                    bindOtherToggle(row);
                }
            });

            // ── Add row ───────────────────────────────────────────────────
            addRowBtn.addEventListener('click', function() {
                const row = buildRow(tbody.children.length + 1);
                tbody.appendChild(row);
                const sel = row.querySelector('.select2-item');
                initSelect2Item(sel, '', '');
                bindOtherToggle(sel.closest('tr'));
                refresh();
            });

            // ── Delete row ────────────────────────────────────────────────
            tbody.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-delete-row');
                if (!btn) return;
                if (tbody.children.length <= 1) return;
                const row = btn.closest('tr');
                const sel = row.querySelector('.select2-item');
                if (sel && $(sel).data('select2')) $(sel).select2('destroy');
                row.remove();
                refresh();
            });

            refresh();

            $('.select2-supplier').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
            $('.select2-department').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
            $('.select2-status').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            const newSupInput = document.getElementById('new_supplier_name');

            $('.select2-supplier').on('select2:select', function(e) {
                const val = e.params.data.id;
                if (val === 'other') {
                    newSupInput.style.display = '';
                    newSupInput.required = true;
                    setTimeout(() => newSupInput.focus(), 50);
                } else {
                    newSupInput.style.display = 'none';
                    newSupInput.required = false;
                    newSupInput.value = '';
                }
            });

            // Restore jika old() = other (setelah validasi gagal)
            @if (old('supplier_id') === 'other')
                newSupInput.style.display = '';
                newSupInput.required = true;
            @endif

        })();
    </script>
@endpush
