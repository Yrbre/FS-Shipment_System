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
                @can('shipment.create')
                <div class="col-auto">
                    <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-sm">
                        <i class="fe fe-plus fe-12 mr-1"></i> New Shipment
                    </a>
                </div>
                @endcan
            </div>

            {{-- ── KPI CARDS ── --}}
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
                                    <span class="fe fe-file fe-32 text-muted"></span>
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
                                    <span class="fe fe-clock fe-32 text-warning"></span>
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
                                    <span class="fe fe-truck fe-32 text-info"></span>
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
                                    <p class="small text-muted mb-0">Semua shipment</p>
                                </div>
                                <div class="col-auto text-right">
                                    <span class="fe fe-box fe-32 text-primary"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- end KPI --}}

            {{-- ── CHART ROW 1: Bar + Doughnut ── --}}
            <div class="row">

                {{-- Chart 1: Shipment per Bulan --}}
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong>Shipment per Bulan</strong>
                            <span class="float-right small text-muted">{{ now()->year }}</span>
                        </div>
                        <div class="card-body">
                            <canvas id="chartBulanan" height="120"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Chart 2: Distribusi Status --}}
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong>Distribusi Status</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="chartStatus" height="200"></canvas>

                            {{-- Legend manual --}}
                            <div class="mt-3">
                                @php
                                    $statusColors = [
                                        'Draft'     => '#6c757d',
                                        'Pending'   => '#ffc107',
                                        'Process'   => '#17a2b8',
                                        'Completed' => '#28a745',
                                        'Rejected'  => '#dc3545',
                                    ];
                                @endphp
                                @foreach ($shipmentByStatus as $statusName => $count)
                                    <div class="row align-items-center mb-1">
                                        <div class="col">
                                            <div class="d-flex align-items-center">
                                                <span class="legend-dot mr-2"
                                                    style="background: {{ $statusColors[$statusName] ?? '#adb5bd' }};"></span>
                                                <small>{{ $statusName }}</small>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted">{{ $count }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── CHART ROW 2: ETA Mendatang ── --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong>Prediksi ETA Shipment</strong>
                            <span class="float-right small text-muted">30 hari ke depan</span>
                        </div>
                        <div class="card-body">
                            @if ($etaData->sum() === 0)
                                <div class="text-center py-4 text-muted">
                                    <span class="fe fe-calendar fe-24 d-block mb-2"></span>
                                    <small>Tidak ada shipment yang akan tiba dalam 30 hari ke depan.</small>
                                </div>
                            @else
                                <canvas id="chartEta" height="60"></canvas>
                            @endif
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
                                    <small>Belum ada shipment.</small>
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
                                                    $sName  = $shipment->status->name ?? '-';
                                                    $sBadge = match($sName) {
                                                        'Pending'   => 'warning',
                                                        'Completed' => 'success',
                                                        'Rejected'  => 'danger',
                                                        'Process'   => 'info',
                                                        'Draft'     => 'secondary',
                                                        default     => 'secondary',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td class="pl-4">
                                                        <strong>{{ $shipment->po ?? '-' }}</strong>
                                                        <div class="small text-muted">{{ $shipment->no_invoice ?? '-' }}</div>
                                                    </td>
                                                    <td>{{ $shipment->supplier->name ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $sBadge }}">{{ $sName }}</span>
                                                    </td>
                                                    <td>
                                                        <small>{{ $shipment->eta ? $shipment->eta->format('d M Y') : '-' }}</small>
                                                    </td>
                                                    <td class="text-right pr-3">
                                                        <a href="{{ route('shipments.show', $shipment->id) }}"
                                                            class="btn btn-sm btn-outline-secondary">
                                                            <i class="fe fe-eye fe-12"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-footer text-right">
                                    <small class="text-muted mr-3">
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

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .fe-32 { font-size: 2rem; }

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
    }

    .legend-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .card-footer {
        background: transparent;
        border-top: 1px solid rgba(0,0,0,0.1);
        padding: 10px 16px;
    }
</style>
@endpush

@push('scripts')
<script>
(function () {

    // ── Deteksi warna teks (dark/light mode) ──────────────────────
    var isDark    = document.body.classList.contains('dark');
    var textColor = isDark ? '#adb5bd' : '#6c757d';
    var gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';

    // ── Chart 1: Bar — Shipment per Bulan ────────────────────────
    var ctxBulanan = document.getElementById('chartBulanan');
    if (ctxBulanan) {
        new Chart(ctxBulanan.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [{
                    label: 'Shipment',
                    data: {!! $chartBulanan !!},
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor:     'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ' ' + ctx.parsed.y + ' shipment';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: textColor },
                        grid:  { color: gridColor },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            stepSize: 1,
                            callback: function(val) {
                                return Number.isInteger(val) ? val : null;
                            }
                        },
                        grid: { color: gridColor },
                    }
                }
            }
        });
    }

    // ── Chart 2: Doughnut — Distribusi Status ────────────────────
    var ctxStatus = document.getElementById('chartStatus');
    if (ctxStatus) {
        var statusLabels = {!! json_encode($shipmentByStatus->keys()) !!};
        var statusData   = {!! json_encode($shipmentByStatus->values()) !!};
        var statusColors = {
            'Draft':     '#6c757d',
            'Pending':   '#ffc107',
            'Process':   '#17a2b8',
            'Completed': '#28a745',
            'Rejected':  '#dc3545',
        };
        var bgColors = statusLabels.map(function(l) {
            return statusColors[l] || '#adb5bd';
        });

        new Chart(ctxStatus.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: bgColors,
                    borderWidth: 2,
                    borderColor: isDark ? '#1e2530' : '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                var total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                var pct   = ((ctx.parsed / total) * 100).toFixed(1);
                                return ' ' + ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // ── Chart 3: Line — ETA Mendatang ────────────────────────────
    var ctxEta = document.getElementById('chartEta');
    if (ctxEta) {
        new Chart(ctxEta.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $etaLabels !!},
                datasets: [{
                    label: 'Prediksi ETA Shipment',
                    data: {!! $etaData !!},
                    fill: true,
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderColor:     'rgba(40, 167, 69, 0.8)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ' ' + ctx.parsed.y + ' Prediksi ETA Shipment';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColor,
                            maxTicksLimit: 10,
                            maxRotation: 0,
                        },
                        grid: { color: gridColor },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            stepSize: 1,
                            callback: function(val) {
                                return Number.isInteger(val) ? val : null;
                            }
                        },
                        grid: { color: gridColor },
                    }
                }
            }
        });
    }

})();
</script>
@endpush
