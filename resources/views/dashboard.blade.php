@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Page Header --}}
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <p class="text-muted small mb-0">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <small class="text-muted mr-2">Filter:</small>
                        <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                            <select name="month" class="form-control form-control-sm mr-2" style="width:100px;"
                                onchange="this.form.submit()">
                                @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] as $i => $bulan)
                                    <option value="{{ $i + 1 }}" {{ $month == $i + 1 ? 'selected' : '' }}>
                                        {{ $bulan }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="form-control form-control-sm mr-2" style="width:85px;"
                                onchange="this.form.submit()">
                                @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endfor
                            </select>
                        </form>
                        @can('shipment.create')
                            <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-sm ml-2">
                                <i class="fe fe-plus fe-12 mr-1"></i> New Shipment
                            </a>
                        @endcan
                    </div>
                </div>

                {{-- ── KPI CARDS ── --}}
                @hasanyrole('Admin|Purchasing|Buyer')
                    <div class="row my-4">

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">Draft</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalDraft) }}</h3>
                                            <p class="small text-muted mb-0">Menunggu Konfirmasi Import</p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <a href="{{ route('shipments.index', ['status' => 'Draft']) }}"
                                                class="text-decoration-none">
                                                <span class="fe fe-file fe-32 text-muted"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">Pending</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalPending) }}</h3>
                                            <p class="small text-muted mb-0">Menunggu konfirmasi</p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <a href="{{ route('shipments.index', ['status' => 'Pending']) }}"
                                                class="text-decoration-none">
                                                <span class="fe fe-clock fe-32 text-warning"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">In Process</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalProcess) }}</h3>
                                            <p class="small text-muted mb-0">Sedang diproses</p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <a href="{{ route('shipments.index', ['status' => 'Process']) }}"
                                                class="text-decoration-none">
                                                <span class="fe fe-truck fe-32 text-info"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">Total Shipment</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalShipment) }}</h3>
                                            <p class="small text-muted mb-0">
                                                {{ \Carbon\Carbon::create()->month($month)->format('M') }} {{ $year }}
                                            </p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <span class="fe fe-box fe-32 text-primary"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ── RECENT SHIPMENT & SUPPLIER ── --}}
                    <div class="row">

                        {{-- Recent Shipments --}}
                        <div class="col-md-8">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <strong class="card-title">Recent Shipment</strong>
                                    <a href="{{ route('shipments.index') }}" class="float-right small text-muted">
                                        View all <i class="fe fe-arrow-right fe-12"></i>
                                    </a>
                                </div>
                                <div class="card-body p-0">
                                    @if ($recentShipments->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <span class="fe fe-inbox fe-24 d-block mb-2"></span>
                                            <small>Belum ada shipment pada periode ini.</small>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover table-borderless mb-0">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th class="pl-4">PO / Invoice</th>
                                                        <th>Supplier</th>
                                                        <th>Status</th>
                                                        <th>ETA</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($recentShipments as $shipment)
                                                        @php
                                                            $sName = $shipment->status->name ?? '-';
                                                            $sBadge = match ($sName) {
                                                                'Pending' => 'warning',
                                                                'Delivered' => 'primary',
                                                                'Rejected' => 'danger',
                                                                'Process' => 'info',
                                                                'Draft' => 'secondary',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <tr>
                                                            <td class="pl-4">
                                                                <strong>{{ $shipment->po ?? '-' }}</strong>
                                                                <div class="small text-muted">
                                                                    {{ $shipment->no_invoice ?? '-' }}</div>
                                                            </td>
                                                            <td>{{ $shipment->supplier->name ?? '-' }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge badge-{{ $sBadge }}">{{ $sName }}</span>
                                                            </td>
                                                            <td>
                                                                <small>{{ $shipment->eta ? $shipment->eta->format('d M Y') : '-' }}</small>
                                                            </td>
                                                            <td class="text-right pr-3">
                                                                <a href="{{ route('shipments.show', $shipment->id) }}"
                                                                    class="btn btn-sm btn-outline-warning">
                                                                    <i class="fe fe-eye fe-12"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="card-footer text-right">
                                            <small class="text-muted">
                                                Showing {{ $recentShipments->count() }} of {{ number_format($totalShipment) }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Recent Suppliers --}}
                        <div class="col-md-4">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <strong class="card-title">Recent Suppliers</strong>
                                </div>
                                <div class="card-body p-0">
                                    @if ($recentSuppliers->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <span class="fe fe-briefcase fe-24 d-block mb-2"></span>
                                            <small>Belum ada supplier.</small>
                                        </div>
                                    @else
                                        <div class="list-group list-group-flush my-n3">
                                            @foreach ($recentSuppliers as $index => $supplier)
                                                <div class="list-group-item">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="avatar-circle">
                                                                {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <strong class="small">{{ $supplier->name }}</strong>
                                                            <div class="my-0 text-muted small">
                                                                {{ $supplier->shipment_count ?? 0 }} shipment
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <span class="badge badge-pill badge-light text-muted">
                                                                #{{ $index + 1 }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endhasanyrole

                @role('User')
                    {{-- KPI --}}
                    <div class="row my-4">

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">Draft</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalDraft) }}</h3>
                                            <p class="small text-muted mb-0">Menunggu Konfirmasi Import</p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <a href="{{ route('shipments.index', ['status' => 'Draft']) }}"
                                                class="text-decoration-none">
                                                <span class="fe fe-file fe-32 text-muted"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">Pending</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalPending) }}</h3>
                                            <p class="small text-muted mb-0">Menunggu konfirmasi</p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <a href="{{ route('shipments.index', ['status' => 'Pending']) }}"
                                                class="text-decoration-none">
                                                <span class="fe fe-clock fe-32 text-warning"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">In Process</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalProcess) }}</h3>
                                            <p class="small text-muted mb-0">Sedang diproses</p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <a href="{{ route('shipments.index', ['status' => 'Process']) }}"
                                                class="text-decoration-none">
                                                <span class="fe fe-truck fe-32 text-info"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small class="text-muted mb-1">Total Shipment</small>
                                            <h3 class="card-title mb-0">{{ number_format($totalShipment) }}</h3>
                                            <p class="small text-muted mb-0">
                                                {{ \Carbon\Carbon::create()->month($month)->format('M') }} {{ $year }}
                                            </p>
                                        </div>
                                        <div class="col-auto text-right">
                                            <span class="fe fe-box fe-32 text-primary"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        {{-- Recent Shipments --}}
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <strong class="card-title">Recent Shipment</strong>
                                    <a href="{{ route('shipments.index') }}" class="float-right small text-muted">
                                        View all <i class="fe fe-arrow-right fe-12"></i>
                                    </a>
                                </div>
                                <div class="card-body p-0">
                                    @if ($recentShipments->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <span class="fe fe-inbox fe-24 d-block mb-2"></span>
                                            <small>Belum ada shipment pada periode ini.</small>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th class="pl-4">PO / Invoice</th>
                                                        <th>Supplier</th>
                                                        <th>Status</th>
                                                        <th>ETA</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($shipmentByUser as $shipment)
                                                        @php
                                                            $sName = $shipment->status->name ?? '-';
                                                            $sBadge = match ($sName) {
                                                                'Pending' => 'warning',
                                                                'Delivered' => 'primary',
                                                                'Rejected' => 'danger',
                                                                'Process' => 'info',
                                                                'Draft' => 'secondary',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <tr>
                                                            <td class="pl-4">
                                                                <strong>{{ $shipment->po ?? '-' }}</strong>
                                                                <div class="small text-muted">
                                                                    {{ $shipment->no_invoice ?? '-' }}</div>
                                                            </td>
                                                            <td>{{ $shipment->supplier->name ?? '-' }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge badge-{{ $sBadge }}">{{ $sName }}</span>
                                                            </td>
                                                            <td>
                                                                <small>{{ $shipment->eta ? $shipment->eta->format('d M Y') : '-' }}</small>
                                                            </td>
                                                            <td class="text-right pr-3">
                                                                <a href="{{ route('shipments.show', $shipment->id) }}"
                                                                    class="btn btn-sm btn-outline-warning">
                                                                    <i class="fe fe-eye fe-12"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="card-footer text-right">
                                            <small class="text-muted">
                                                Showing {{ $shipmentByUser->count() }} of
                                                {{ number_format($totalShipmentByUser) }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endrole

                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .fe-32 {
                font-size: 2rem;
            }

            .avatar-circle {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background-color: #e9ecef;
                color: #495057;
                font-size: 0.72rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                letter-spacing: 0.05em;
                transition: transform 0.2s ease, background-color 0.2s ease;
            }

            .list-group-item:hover .avatar-circle {
                transform: scale(1.1);
                background-color: #007bff;
                color: #fff;
            }

            .card-footer {
                background: transparent;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                padding: 10px 16px;
            }

            /* Fade-in card */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(12px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card {
                animation: fadeInUp 0.35s ease both;
            }

            .row .col-md-3:nth-child(1) .card {
                animation-delay: 0.05s;
            }

            .row .col-md-3:nth-child(2) .card {
                animation-delay: 0.10s;
            }

            .row .col-md-3:nth-child(3) .card {
                animation-delay: 0.15s;
            }

            .row .col-md-3:nth-child(4) .card {
                animation-delay: 0.20s;
            }

            /* Hover lift */
            .card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
            }
        </style>
    @endpush
