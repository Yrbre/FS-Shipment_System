@extends('layouts.app')

@section('title', 'Detail Shipment')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">

            {{-- Breadcrumb & Actions --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span>
                    <a href="{{ route('shipments.index') }}" class="text-secondary text-decoration-none">Shipment</a>
                    <span class="text-secondary mx-1">/</span>
                    <span class="text-primary">{{ $shipment->po }}</span>
                </span>
                <div class="d-flex">
                    <div class="mr-2">
                    <a href="{{ route('shipments.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back
                    </a>
                    </div>
                    {{-- Tampilkan edit hanya jika status masih awal --}}
                    @if (now() < $shipment->etd)
                    @can('shipment.edit')
                        <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Update Shipment
                        </a>
                    @endcan
                    @endif
                </div>
            </div>

            {{-- Status Bar --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body py-3">
                            <p class="text-muted small text-uppercase mb-1 fw-semibold">Status</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-primary d-inline-block mr-2"
                                    style="width:10px; height:10px; flex-shrink:0;"></span>
                                <span class="fw-bold text-primary">{{ $shipment->status->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body py-3">
                            <p class="text-muted small text-uppercase mb-1 fw-semibold">Supplier</p>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-building text-secondary mr-2"></i>
                                <span class="fw-bold text-white">{{ $shipment->supplier->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body py-3">
                            <p class="text-muted small text-uppercase mb-1 fw-semibold">ETD</p>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-calendar-minus text-secondary mr-2"></i>
                                <span class="fw-bold text-white">
                                    {{ $shipment->etd ? $shipment->etd->format('d M Y') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body py-3">
                            <p class="text-muted small text-uppercase mb-1 fw-semibold">ETA</p>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-calendar-check text-secondary mr-2"></i>
                                <span class="fw-bold text-white">
                                    {{ $shipment->eta ? $shipment->eta->format('d M Y') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="row g-4">

                {{-- Left Column --}}
                <div class="col-lg-8">

                    {{-- Shipment Information --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-info text-primary mr-2"></i>
                            <h5 class="mb-0 text-white">Shipment Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Purchase Order</p>
                                    <p class="fw-semibold text-white mb-0">{{ $shipment->po }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">No Invoice</p>
                                    <p class="fw-semibold text-white mb-0">{{ $shipment->no_invoice }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">No B/L</p>
                                    <p class="fw-semibold text-white mb-0">{{ $shipment->no_bl }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Department</p>
                                    <p class="fw-semibold text-white mb-0">{{ $shipment->department->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Created By</p>
                                    <p class="fw-semibold text-white mb-0">{{ $shipment->creator->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Created At</p>
                                    <p class="fw-semibold text-white mb-0">
                                        {{ $shipment->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                @if ($shipment->notes)
                                    <div class="col-12">
                                        <p class="text-muted small mb-1">Notes</p>
                                        <p class="fw-semibold text-white mb-0">{{ $shipment->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-boxes-stacked text-primary mr-2"></i>
                                <h5 class="mb-0 text-white">Shipment Items</h5>
                            </div>
                            <span class="badge bg-info text-white">
                                {{ $shipment->items->count() }} item{{ $shipment->items->count() > 1 ? 's' : '' }}
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width:44px;">#</th>
                                        <th>Item</th>
                                        <th style="width:12%;">HS Code</th>
                                        <th style="width:10%;">Qty</th>
                                        <th style="width:10%;">UOM</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($shipment->items as $index => $shipmentItem)
                                        <tr>
                                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-semibold text-white">
                                                    {{ $shipmentItem->item->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary font-monospace">
                                                    {{ $shipmentItem->hscode ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="fw-semibold">{{ $shipmentItem->quantity }}</td>
                                            <td class="text-muted">{{ $shipmentItem->uom }}</td>
                                            <td class="text-muted">{{ $shipmentItem->notes ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fa-solid fa-box-open me-2"></i> Tidak ada item
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- Right Column: History Timeline --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                            <h5 class="mb-0 text-white">Update History</h5>
                        </div>
                        <div class="card-body p-0">
                            @forelse ($shipment->histories->sortByDesc('created_at') as $history)
                                <div
                                    class="d-flex gap-3 px-3 py-3
                                {{ !$loop->last ? 'border-bottom' : '' }}">

                                    {{-- Timeline dot --}}
                                    <div class="d-flex flex-column align-items-center flex-shrink-0 pt-1">
                                        @if ($loop->first)
                                            <div class="rounded-circle bg-primary mr-2"
                                                style="width:12px; height:12px; box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.2);">
                                            </div>
                                        @else
                                            <div class="rounded-circle bg-secondary mr-2"
                                                style="width:12px; height:12px; opacity: 0.5;">
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content --}}

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <span class="fw-semibold text-white small">
                                                {{ $history->status->name ?? 'Updated' }}
                                            </span>
                                            <span class="text-muted" style="font-size:11px; white-space:nowrap;">
                                                {{ $history->created_at->format('H:i') }}
                                            </span>
                                        </div>

                                        @if ($history->notes)
                                            <p class="text-muted mb-1" style="font-size:13px;">
                                                {{ $history->notes }}
                                            </p>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-between gap-1 text-muted" style="font-size:12px;">
                                            <span><i class="fa-solid fa-user mr-2" style="font-size:10px;"></i> {{ $history->changedBy ? $history->changedBy->name : $history->creator->name ?? '-' }}</span>
                                            <a href="{{ route('shipments.history', [$shipment->id, $history->id]) }}" class="btn btn-sm btn-outline-warning mt-2">
                                                <i class="fa-solid fa-eye me-1"></i> View History Detail
                                            </a>
                                        </div>
                                            <p class="text-muted mb-0 mt-1" style="font-size:11px;">
                                                {{ $history->created_at->format('d M Y') }}
                                            </p>
                                    </div>
                                </div>

                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fa-solid fa-timeline me-2"></i>
                                    Belum ada history
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
