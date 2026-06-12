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
            @can('shipment.edit')
                <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            @endcan
            </div>
            <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Status Badge --}}
    @php
        $statusName = $shipment->status->name ?? '';
        $statusClass = match($statusName) {
            'Pending'    => 'warning',
            'Completed'  => 'success',
            'Rejected'   => 'danger',
            'On The Way' => 'info',
            default      => 'secondary',
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
                {{-- Kolom Kiri --}}
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                        Informasi Dokumen
                    </h6>
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted pl-0" style="width: 40%;">No. PO</td>
                                <td class="font-weight-medium">{{ $shipment->po ?? '-' }}</td>
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

                {{-- Kolom Kanan --}}
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="font-size: 0.7rem; letter-spacing: 0.08em;">
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

            {{-- Items Shipment --}}
            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                Item Shipment
            </h6>

            @if($shipment->items->isEmpty())
                <p class="text-muted fst-italic">Tidak ada item.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Nama Item</th>
                                <th>Kode</th>
                                <th>Qty</th>
                                <th>UOM</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipment->items as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->item->name ?? '-' }}</td>
                                    <td><code>{{ $item->item->code ?? '-' }}</code></td>
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

            @if($shipment->histories->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-history fa-2x mb-2 d-block"></i>
                    Belum ada history perubahan.
                </div>
            @else
                {{-- Timeline --}}
                <div class="timeline-wrapper">
                    @foreach($shipment->histories->sortByDesc('created_at') as $history)
                        @php
                            $hStatusName  = $history->status->name ?? '-';
                            $hStatusClass = match($hStatusName) {
                                'Pending'    => 'warning',
                                'Completed'  => 'success',
                                'Rejected'   => 'danger',
                                'On The Way' => 'info',
                                default      => 'secondary',
                            };
                        @endphp

                        <div class="timeline-item mb-4">
                            {{-- Header accordion --}}
                            <div class="card border-left-{{ $hStatusClass }} shadow-none">
                                <div class="card-header bg-white py-2 px-3 d-flex align-items-center justify-content-between"
                                     data-toggle="collapse"
                                     data-target="#hist-{{ $history->id }}"
                                     style="cursor: pointer;">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge badge-{{ $hStatusClass }}">{{ $hStatusName }}</span>
                                        <small class="text-muted ml-2">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            {{ $history->createdBy->name ?? '-' }}
                                        </small>
                                        @if($history->notes)
                                            <small class="text-muted ml-2">
                                                <i class="fas fa-comment-alt mr-1"></i>
                                                {{ $history->notes }}
                                            </small>
                                        @endif
                                    </div>
                                    <small class="text-muted text-nowrap ml-3">
                                        {{ $history->created_at->format('d M Y, H:i') }}
                                    </small>
                                </div>

                                {{-- Collapsible body --}}
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
                                                            <td>{{ $history->po ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted pl-0">No. Invoice</td>
                                                            <td>{{ $history->no_invoice ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted pl-0">No. BL</td>
                                                            <td>{{ $history->no_bl ?? '-' }}</td>
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
                                                            <td class="text-muted pl-0" style="width:40%">Supplier</td>
                                                            <td>{{ $history->supplier->name ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted pl-0">ETD</td>
                                                            <td>{{ $history->etd ? \Carbon\Carbon::parse($history->etd)->format('d M Y') : '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted pl-0">ETA</td>
                                                            <td>{{ $history->eta ? \Carbon\Carbon::parse($history->eta)->format('d M Y') : '-' }}</td>
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

                                        @if($history->items->isEmpty())
                                            <p class="text-muted small fst-italic mb-0">Tidak ada item tercatat.</p>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th>Nama Item</th>
                                                            <th>Kode</th>
                                                            <th>Qty</th>
                                                            <th>UOM</th>
                                                            <th>Catatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($history->items as $hi => $hItem)
                                                            <tr>
                                                                <td>{{ $hi + 1 }}</td>
                                                                <td>{{ $hItem->item->name ?? '-' }}</td>
                                                                <td><code>{{ $hItem->item->code ?? '-' }}</code></td>
                                                                <td>{{ $hItem->quantity ?? '-' }}</td>
                                                                <td>{{ $hItem->uom ?? '-' }}</td>
                                                                <td>{{ $hItem->notes ?? '-' }}</td>
                                                            </tr>
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

    </div>{{-- end tab-content --}}
</div>
@endsection

@push('styles')
<style>
    .border-left-warning  { border-left: 4px solid #ffc107 !important; }
    .border-left-success  { border-left: 4px solid #28a745 !important; }
    .border-left-danger   { border-left: 4px solid #dc3545 !important; }
    .border-left-info     { border-left: 4px solid #17a2b8 !important; }
    .border-left-secondary{ border-left: 4px solid #6c757d !important; }

    .nav-tabs .nav-link {
        color: #6c757d;
        font-size: 0.875rem;
    }
    .nav-tabs .nav-link.active {
        font-weight: 600;
        color: #343a40;
    }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }

    .timeline-item .card-header:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endpush
