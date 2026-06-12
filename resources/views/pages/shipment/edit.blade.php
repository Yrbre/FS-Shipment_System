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
                                <input type="text"
                                    class="form-control @error('po') is-invalid @enderror"
                                    id="po" name="po"
                                    value="{{ old('po', $shipment->po) }}"
                                    placeholder="e.g. PO-2024-001">
                                @error('po')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="no_invoice" class="form-label text-white">
                                    No Invoice <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('no_invoice') is-invalid @enderror"
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
                                <input type="text"
                                    class="form-control @error('no_bl') is-invalid @enderror"
                                    id="no_bl" name="no_bl"
                                    value="{{ old('no_bl', $shipment->no_bl) }}"
                                    placeholder="e.g. BL-2024-001">
                                @error('no_bl')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Supplier --}}
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
                                    </optgroup>
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Department --}}
                            <div class="col-md-6">
                                <label for="department_id" class="form-label text-white">
                                    Department <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2-department @error('department_id') is-invalid @enderror"
                                    id="department_id" name="department_id">
                                    <optgroup label="Select Department">
                                        <option value="" disabled>Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $shipment->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
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
                                <input type="date"
                                    class="form-control @error('etd') is-invalid @enderror"
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
                                <input type="date"
                                    class="form-control @error('eta') is-invalid @enderror"
                                    id="eta" name="eta"
                                    value="{{ old('eta', $shipment->eta?->format('Y-m-d')) }}">
                                @error('eta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="notes" class="form-label text-white">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                    id="notes" name="notes" rows="2"
                                    placeholder="Additional notes...">{{ old('notes', $shipment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            {{-- Section 2: Update Status (hanya untuk yang punya permission) --}}
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
                                <label for="status_id" class="form-label text-white">
                                    Status Baru
                                </label>
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
                                    id="status_notes" name="status_notes"
                                    value="{{ old('status_notes') }}"
                                    placeholder="e.g. Barang sudah berangkat dari pelabuhan...">
                                @error('status_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Status flow hint --}}
                        @if ($statuses->count() > 0)
                        <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                            @foreach ($statuses as $status)
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge {{ $shipment->status_id == $status->id ? 'bg-primary' : 'bg-secondary opacity-50' }}">
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

                        @if ($errors->hasAny(['item_id', 'item_id.*', 'quantity.*', 'uom.*', 'new_item_name.*']))
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
                                        <th style="width:32%;">Item</th>
                                        <th style="width:16%;">New Item Name</th>
                                        <th style="width:9%;">Qty</th>
                                        <th style="width:13%;">UOM</th>
                                        <th>Notes</th>
                                        <th style="width:52px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">

                                    @php
                                        // Jika ada old() (validation fail), pakai old — jika tidak, pakai data shipment
                                        $oldItemIds = old('item_id');
                                        $useOld     = !is_null($oldItemIds);
                                        $rows       = $useOld
                                            ? array_keys($oldItemIds)
                                            : $shipment->items->keys()->toArray();
                                    @endphp

                                    @foreach ($rows as $index)
                                        @php
                                            if ($useOld) {
                                                $itemId       = old("item_id.{$index}");
                                                $isOther      = $itemId === 'other';
                                                $quantity     = old("quantity.{$index}");
                                                $uom          = old("uom.{$index}");
                                                $itemNote     = old("item_notes.{$index}");
                                                $newItemName  = old("new_item_name.{$index}");
                                                $shipmentItemId = old("shipment_item_id.{$index}");
                                            } else {
                                                $si           = $shipment->items[$index];
                                                $itemId       = $si->item_id;
                                                $isOther      = false;
                                                $quantity     = $si->quantity + 0;
                                                $uom          = $si->uom;
                                                $itemNote     = $si->notes;
                                                $newItemName  = '';
                                                $shipmentItemId = $si->id;
                                            }
                                        @endphp
                                        <tr>
                                            {{-- Hidden: shipment_item_id untuk update/delete existing --}}
                                            <input type="hidden" name="shipment_item_id[]" value="{{ $shipmentItemId }}">

                                            <td class="text-center text-muted row-num">{{ $index + 1 }}</td>

                                            {{-- Item Select --}}
                                            <td>
                                                <select class="form-control form-control-sm select2-item @error('item_id.'.$index) is-invalid @enderror"
                                                    name="item_id[]">
                                                    <option value="" disabled>-- Select Item --</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-uom="{{ $item->uom }}"
                                                            {{ $itemId == $item->id ? 'selected' : '' }}>
                                                            [{{ $item->code }}] {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                    <option value="other" {{ $isOther ? 'selected' : '' }}>
                                                        ➕ Other (New Item)
                                                    </option>
                                                </select>
                                                @error('item_id.'.$index)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            {{-- New Item Name --}}
                                            <td>
                                                <input type="text"
                                                    class="form-control form-control-sm new-item-name @error('new_item_name.'.$index) is-invalid @enderror"
                                                    name="new_item_name[]"
                                                    value="{{ $newItemName }}"
                                                    placeholder="Item name..."
                                                    {{ $isOther ? '' : 'readonly' }}
                                                    style="{{ $isOther ? '' : 'opacity:.4; background:transparent;' }}">
                                                @error('new_item_name.'.$index)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            <td>
                                                <input type="number"
                                                    class="form-control form-control-sm @error('quantity.'.$index) is-invalid @enderror"
                                                    name="quantity[]"
                                                    value="{{ $quantity }}"
                                                    placeholder="0" min="0" step="0.01">
                                                @error('quantity.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            <td>
                                                <input type="text"
                                                    class="form-control form-control-sm item-uom @error('uom.'.$index) is-invalid @enderror"
                                                    name="uom[]"
                                                    value="{{ $uom }}"
                                                    placeholder="e.g. PCS">
                                                @error('uom.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            <td>
                                                <input type="text"
                                                    class="form-control form-control-sm"
                                                    name="item_notes[]"
                                                    value="{{ $itemNote }}"
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
                <div class="d-flex">
                    <div class="mr-2">
                    <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {

    const tbody     = document.getElementById('items-body');
    const addRowBtn = document.getElementById('btn-add-row');
    const badge     = document.getElementById('item-count-badge');

    // ── Simpan options HTML SEBELUM Select2 di-init ───────────────
    const firstSelect     = tbody.querySelector('.select2-item');
    const itemOptionsHTML = firstSelect ? firstSelect.innerHTML : '';

    function initSelect2Item(el) {
        $(el).select2({ theme: 'bootstrap4', width: '100%' });
    }

    function bindOtherToggle(row) {
        const sel       = row.querySelector('.select2-item');
        const nameInput = row.querySelector('.new-item-name');
        const uomInput  = row.querySelector('.item-uom');

        $(sel).on('change', function () {
            const val = $(this).val();
            if (val === 'other') {
                nameInput.readOnly         = false;
                nameInput.style.opacity    = '1';
                nameInput.style.background = '';
                uomInput.value    = '';
                uomInput.readOnly = false;
                setTimeout(() => nameInput.focus(), 50);
            } else {
                nameInput.readOnly         = true;
                nameInput.style.opacity    = '.4';
                nameInput.style.background = 'transparent';
                nameInput.value            = '';
                const uom = $(this).find(':selected').data('uom') || '';
                uomInput.value    = uom;
                uomInput.readOnly = !!uom;
            }
        });
    }

    function buildRow(num) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <input type="hidden" name="shipment_item_id[]" value="">
            <td class="text-center text-muted row-num">${num}</td>
            <td>
                <select class="form-control form-control-sm select2-item" name="item_id[]">
                    ${itemOptionsHTML}
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm new-item-name"
                    name="new_item_name[]" placeholder="Item name..."
                    readonly style="opacity:.4; background:transparent;">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm"
                    name="quantity[]" placeholder="0" min="0" step="0.01">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm item-uom"
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
        if (sel) {
            initSelect2Item(sel);
            bindOtherToggle(row);
            $(sel).trigger('change');
        }
    });

    // ── Add row ───────────────────────────────────────────────────
    addRowBtn.addEventListener('click', function () {
        const row = buildRow(tbody.children.length + 1);
        tbody.appendChild(row);
        const sel = row.querySelector('.select2-item');
        initSelect2Item(sel);
        bindOtherToggle(sel.closest('tr'));
        $(sel).trigger('change');
        refresh();
    });

    // ── Delete row ────────────────────────────────────────────────
    tbody.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-row');
        if (!btn) return;
        if (tbody.children.length <= 1) return;
        const row = btn.closest('tr');
        const sel = row.querySelector('.select2-item');
        if (sel && $(sel).data('select2')) $(sel).select2('destroy');
        row.remove();
        refresh();
    });

    // ── Supplier, Department & Status Select2 ────────────────────
    $('.select2-supplier').select2({ theme: 'bootstrap4' });
    $('.select2-department').select2({ theme: 'bootstrap4' });
    $('.select2-status').select2({ theme: 'bootstrap4' });

    refresh();

})();
</script>
@endpush
