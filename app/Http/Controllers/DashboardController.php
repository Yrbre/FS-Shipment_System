<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDraft    = Shipment::whereHas('status', fn($q) => $q->where('name', 'Draft'))->count();
        $totalPending  = Shipment::whereHas('status', fn($q) => $q->where('name', 'Pending'))->count();
        $totalProcess  = Shipment::whereHas('status', fn($q) => $q->where('name', 'Process'))->count();
        $totalShipment = Shipment::count();

        $recentShipments = Shipment::with(['supplier', 'status', 'department'])
            ->latest()
            ->take(5)
            ->get();

        $recentSuppliers = Supplier::withCount('shipment')
            ->latest()
            ->take(5)
            ->get();

        // ── Chart 1: Shipment per Bulan ──
        $shipmentPerBulan = Shipment::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $chartBulanan = collect(range(1, 12))
            ->map(fn($m) => $shipmentPerBulan[$m] ?? 0)
            ->values();

        // ── Chart 2: Distribusi Status ──
        $shipmentByStatus = Shipment::select('statuses.name', DB::raw('COUNT(*) as total'))
            ->join('statuses', 'shipments.status_id', '=', 'statuses.id')
            ->groupBy('statuses.name')
            ->pluck('total', 'name');

        // ── Chart 3: ETA Mendatang 30 hari ──
        $etaMendatang = Shipment::selectRaw('DATE(eta) as tanggal, COUNT(*) as total')
            ->whereBetween('eta', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
            ->whereHas('status', fn($q) => $q->whereNotIn('name', ['Completed', 'Rejected']))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Fill semua tanggal 30 hari ke depan (agar line chart tidak bolong)
        $etaLabels = collect();
        $etaData   = collect();
        for ($i = 0; $i <= 30; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $etaLabels->push(now()->addDays($i)->format('d M'));
            $etaData->push($etaMendatang->firstWhere('tanggal', $date)?->total ?? 0);
        }

        return view('dashboard', compact(
            'totalDraft',
            'totalPending',
            'totalProcess',
            'totalShipment',
            'recentShipments',
            'recentSuppliers',
            'chartBulanan',
            'shipmentByStatus',
            'etaLabels',
            'etaData',
        ));
    }
}
