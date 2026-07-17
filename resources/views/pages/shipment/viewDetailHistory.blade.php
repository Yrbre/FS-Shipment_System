@extends('layouts.app')

@section('title', 'Detail Shipment')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0 font-weight-bold text-dark">Detail Shipment</h4>
                <small class="text-muted">PO: {{ $shipment->po ?? '-' }}</small>
            </div>
            <div class="d-flex">
                <div class="mr-2">
                    <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>

                </div>
                @hasanyrole(['Admin', 'Import'])
                    @can('shipment.edit')
                        <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    @endcan
                @endhasanyrole
            </div>
        </div>

        {{-- Status Badge --}}
        @php
            $statusName = $shipment->status->name ?? '';
            $statusClass = match ($statusName) {
                'Pending' => 'warning',
                'Delivered' => 'primary',
                'Rejected' => 'danger',
                'Process' => 'info',
                default => 'secondary',
            };
        @endphp
        <div class="mb-3">
            <span class="badge badge-{{ $statusClass }} px-3 py-2" style="font-size: 0.85rem;">
                {{ $statusName ?: '-' }}
            </span>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs" id="shipmentTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab">
                    <i class="fas fa-info-circle mr-1"></i> Info Shipment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab">
                    <i class="fas fa-history mr-1"></i> History
                    <span class="badge badge-secondary ml-1">{{ $shipment->histories->count() }}</span>
                </a>
            </li>
        </ul>

        <div class="tab-content border border-top-0 rounded-bottom bg-white shadow-sm" id="shipmentTabContent">

            {{-- ── TAB 1: INFO SHIPMENT ── --}}
            <div class="tab-pane fade show active p-4" id="info" role="tabpanel">

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-3"
                            style="font-size: 0.7rem; letter-spacing: 0.08em;">
                            Informasi Dokumen
                        </h6>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted pl-0" style="width: 40%;">No. PO</td>
                                    <td>{{ $shipment->po ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">No. Invoice</td>
                                    <td>{{ $shipment->no_invoice ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">No. BL</td>
                                    <td>{{ $shipment->no_bl ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">Supplier</td>
                                    <td>{{ $shipment->supplier->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">Department</td>
                                    <td>{{ $shipment->department->name ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-3"
                            style="font-size: 0.7rem; letter-spacing: 0.08em;">
                            Jadwal & Catatan
                        </h6>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted pl-0" style="width: 40%;">ETD</td>
                                    <td>{{ $shipment->etd ? $shipment->etd->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">ETA</td>
                                    <td>{{ $shipment->eta ? $shipment->eta->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">Dibuat Oleh</td>
                                    <td>{{ $shipment->creator->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">Dibuat Pada</td>
                                    <td>{{ $shipment->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">Catatan</td>
                                    <td>{{ $shipment->notes ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <h6 class="text-uppercase text-muted font-weight-bold mb-3"
                    style="font-size: 0.7rem; letter-spacing: 0.08em;">
                    Item Shipment
                </h6>

                @if ($shipment->items->isEmpty())
                    <p class="text-muted fst-italic">Tidak ada item.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama Item</th>
                                    <th>HS Code</th>
                                    <th>Qty</th>
                                    <th>UOM</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipment->items as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->item->name ?? '-' }}</td>
                                        <td>{{ $item->hscode ?? '-' }}</td>
                                        <td>{{ $item->quantity ?? '-' }}</td>
                                        <td>{{ $item->uom ?? '-' }}</td>
                                        <td>{{ $item->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ── TAB 2: HISTORY ── --}}
            <div class="tab-pane fade p-4" id="history" role="tabpanel">

                @if ($shipment->histories->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-history fa-2x mb-2 d-block"></i>
                        Belum ada history perubahan.
                    </div>
                @else
                    <div class="timeline-wrapper">
                        @foreach ($shipment->histories->sortByDesc('created_at') as $history)
                            @php
                                $diff = $historyDiffs[$history->id];
                                $prev = $diff['prev'];
                                $changedFields = $diff['changedFields'];
                                $addedItemIds = $diff['addedItemIds'];
                                $removedItemIds = $diff['removedItemIds'];
                                $changedItemFields = $diff['changedItemFields'];
                                $prevItems = $diff['prevItems'];

                                $hStatusName = $history->status->name ?? '-';
                                $hStatusClass = match ($hStatusName) {
                                    'Pending' => 'warning',
                                    'Delivered' => 'primary',
                                    'Rejected' => 'danger',
                                    'Process' => 'info',
                                    default => 'secondary',
                                };
                            @endphp

                            <div class="timeline-item mb-4">
                                <div class="card border-left-{{ $hStatusClass }} shadow-none">
                                    <div class="card-header bg-white py-2 px-3 d-flex align-items-center justify-content-between"
                                        data-toggle="collapse" data-target="#hist-{{ $history->id }}"
                                        style="cursor: pointer;">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge badge-{{ $hStatusClass }}">{{ $hStatusName }}</span>
                                            <small class="text-muted">
                                                <i class="fas fa-user-circle mt-2"></i>
                                                {{ $history->creator->name ?? '-' }}
                                            </small>
                                            @if ($history->notes)
                                                <small class="text-muted">
                                                    <i class="fas fa-comment-alt mt-2"></i>
                                                    {{ $history->notes }}
                                                </small>
                                            @endif
                                        </div>
                                        <small class="text-muted text-nowrap ml-3">
                                            {{ $history->created_at->format('d M Y, H:i') }}
                                        </small>
                                    </div>

                                    <div id="hist-{{ $history->id }}" class="collapse">
                                        <div class="card-body pt-2 pb-3 px-3">


                                            {{-- Info Snapshot --}}
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <small class="text-uppercase text-muted font-weight-bold d-block mb-2"
                                                        style="font-size: 0.65rem; letter-spacing: 0.07em;">
                                                        Data Shipment
                                                    </small>
                                                    <table class="table table-sm table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-muted pl-0" style="width:40%">No. PO</td>
                                                                <td>
                                                                    @if (in_array('po', $changedFields))
                                                                        <span
                                                                            class="badge badge-warning">{{ $history->po ?? '-' }}</span>
                                                                    @else
                                                                        {{ $history->po ?? '-' }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted pl-0">No. Invoice</td>
                                                                <td>
                                                                    @if (in_array('no_invoice', $changedFields))
                                                                        <span
                                                                            class="badge badge-warning">{{ $history->no_invoice ?? '-' }}</span>
                                                                    @else
                                                                        {{ $history->no_invoice ?? '-' }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted pl-0">No. BL</td>
                                                                <td>
                                                                    @if (in_array('no_bl', $changedFields))
                                                                        <span
                                                                            class="badge badge-warning">{{ $history->no_bl ?? '-' }}</span>
                                                                    @else
                                                                        {{ $history->no_bl ?? '-' }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted pl-0">Status</td>
                                                                <td>
                                                                    @if (in_array('status_id', $changedFields))
                                                                        <span
                                                                            class="badge badge-warning">{{ $hStatusName }}</span>
                                                                    @else
                                                                        {{ $hStatusName }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-uppercase text-muted font-weight-bold d-block mb-2"
                                                        style="font-size: 0.65rem; letter-spacing: 0.07em;">
                                                        Jadwal
                                                    </small>
                                                    <table class="table table-sm table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-muted pl-0" style="width:40%">Supplier
                                                                </td>
                                                                <td>
                                                                    @if (in_array('supplier_id', $changedFields))
                                                                        <span
                                                                            class="badge badge-warning">{{ $history->supplier->name ?? '-' }}</span>
                                                                    @else
                                                                        {{ $history->supplier->name ?? '-' }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted pl-0">ETD</td>
                                                                <td>
                                                                    @if (in_array('etd', $changedFields))
                                                                        <span class="badge badge-warning">
                                                                            {{ $history->etd ? \Carbon\Carbon::parse($history->etd)->format('d M Y') : '-' }}
                                                                        </span>
                                                                    @else
                                                                        {{ $history->etd ? \Carbon\Carbon::parse($history->etd)->format('d M Y') : '-' }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted pl-0">ETA</td>
                                                                <td>
                                                                    @if (in_array('eta', $changedFields))
                                                                        <span class="badge badge-warning">
                                                                            {{ $history->eta ? \Carbon\Carbon::parse($history->eta)->format('d M Y') : '-' }}
                                                                        </span>
                                                                    @else
                                                                        {{ $history->eta ? \Carbon\Carbon::parse($history->eta)->format('d M Y') : '-' }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            {{-- Items Snapshot --}}
                                            <small class="text-uppercase text-muted font-weight-bold d-block mb-2"
                                                style="font-size: 0.65rem; letter-spacing: 0.07em;">
                                                Item pada saat ini
                                            </small>

                                            @if ($history->items->isEmpty())
                                                <p class="text-muted small fst-italic mb-0">Tidak ada item tercatat.</p>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th>Nama Item</th>
                                                                <th>HS Code</th>
                                                                <th>Qty</th>
                                                                <th>UOM</th>
                                                                <th>Catatan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($history->items as $hi => $hItem)
                                                                @php
                                                                    $isNew = in_array($hItem->item_id, $addedItemIds);
                                                                    $itemCh = $changedItemFields[$hItem->item_id] ?? [];
                                                                @endphp
                                                                <tr class="{{ $isNew ? 'row-added' : '' }}">
                                                                    <td>{{ $hi + 1 }}</td>
                                                                    <td>
                                                                        @if (in_array('item_id', $itemCh))
                                                                            <span
                                                                                class="badge badge-warning">{{ $hItem->item->name ?? '-' }}</span>
                                                                        @else
                                                                            {{ $hItem->item->name ?? '-' }}
                                                                        @endif
                                                                        @if ($isNew)
                                                                            <span class="badge badge-success ml-1"
                                                                                style="font-size:0.65rem;">Baru</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (in_array('hscode', $itemCh))
                                                                            <span
                                                                                class="badge badge-warning">{{ $hItem->hscode ?? '-' }}</span>
                                                                        @else
                                                                            {{ $hItem->hscode ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (in_array('quantity', $itemCh))
                                                                            <span
                                                                                class="badge badge-warning">{{ $hItem->quantity ?? '-' }}</span>
                                                                        @else
                                                                            {{ $hItem->quantity ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (in_array('uom', $itemCh))
                                                                            <span
                                                                                class="badge badge-warning">{{ $hItem->uom ?? '-' }}</span>
                                                                        @else
                                                                            {{ $hItem->uom ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (in_array('notes', $itemCh))
                                                                            <span
                                                                                class="badge badge-warning">{{ $hItem->notes ?? '-' }}</span>
                                                                        @else
                                                                            {{ $hItem->notes ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            {{-- Item yang dihapus --}}
                                                            @foreach ($removedItemIds as $removedId)
                                                                @php $removedItem = $prevItems->get($removedId); @endphp
                                                                @if ($removedItem)
                                                                    <tr class="row-removed">
                                                                        <td>-</td>
                                                                        <td>
                                                                            {{ $removedItem->item->name ?? '-' }}
                                                                            <span class="badge badge-danger ml-1"
                                                                                style="font-size:0.65rem;">Dihapus</span>
                                                                        </td>
                                                                        <td>{{ $removedItem->hscode ?? '-' }}</td>
                                                                        <td>{{ $removedItem->quantity ?? '-' }}</td>
                                                                        <td>{{ $removedItem->uom ?? '-' }}</td>
                                                                        <td>{{ $removedItem->notes ?? '-' }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .border-left-secondary {
            border-left: 4px solid #6c757d !important;
        }

        .row-added {
            background-color: #d4edda !important;
        }

        .row-removed {
            background-color: #f8d7da !important;
            text-decoration: line-through;
            opacity: 0.8;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
            color: #343a40;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 0.75rem;
        }

        .timeline-item .card-header:hover {
            background-color: #f8f9fa !important;
        }
    </style>
@endpush
