<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year  = (int) request('year',  now()->year);

        $base = Shipment::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month);

        if (!auth()->user()->hasAnyRole(['Admin','Import','Buyer'])) {
            $base->where('department_id', auth()->user()->department_id);
        }

        $totalDraft    = (clone $base)->whereHas('status', fn($q) => $q->where('name', 'Draft'))->count();
        $totalPending  = (clone $base)->whereHas('status', fn($q) => $q->where('name', 'Pending'))->count();
        $totalProcess  = (clone $base)->whereHas('status', fn($q) => $q->where('name', 'Process'))->count();
        $totalShipment = (clone $base)->count();

        $recentShipments = Shipment::with(['supplier', 'status'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->take(5)
            ->get();

        $recentSuppliers = Supplier::withCount('shipment')
            ->latest()
            ->take(5)
            ->get();

        $shipmentByUser = Shipment::where('department_id', auth()->user()->department_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->take(10)
            ->get();

        $totalShipmentByUser = Shipment::where('department_id', auth()->user()->department_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        return view('dashboard', compact(
            'totalDraft', 'totalPending', 'totalProcess', 'totalShipment',
            'recentShipments', 'recentSuppliers', 'shipmentByUser','totalShipmentByUser',
            'month', 'year'
        ));
    }
}
