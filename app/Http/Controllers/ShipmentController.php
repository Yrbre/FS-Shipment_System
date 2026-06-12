<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateShipmentRequest;
use App\Models\Item;
use App\Services\MasterData\DepartmentService;
use App\Services\MasterData\ItemService;
use App\Services\MasterData\StatusService;
use App\Services\MasterData\SupplierService;
use App\Services\ShipmentService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShipmentController extends Controller
{
    private ShipmentService $shipmentService;
    private SupplierService $supplierService;
    private StatusService $statusService;
    private DepartmentService $departmentService;
    private ItemService $itemService;
    public function __construct(
        ShipmentService $shipmentService,
        SupplierService $supplierService,
        StatusService $statusService,
        DepartmentService $departmentService,
        ItemService $itemService
    ) {
        $this->shipmentService = $shipmentService;
        $this->supplierService = $supplierService;
        $this->statusService = $statusService;
        $this->departmentService = $departmentService;
        $this->itemService = $itemService;
        $this->middleware('permission:shipment.view')->only(['index', 'show']);
        $this->middleware('permission:shipment.create')->only(['create', 'store']);
        $this->middleware('permission:shipment.edit')->only(['edit', 'update']);
        $this->middleware('permission:shipment.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = auth()->user();
                if ($user->hasAnyRole(['Admin', 'Purchasing', 'IMC'])) {
                    $shipments = $this->shipmentService->getAll();
                } else {
                    $shipments = $this->shipmentService->getByDepartment($user->department_id);
                }
                return DataTables::of($shipments)
                    ->addIndexColumn()
                    ->addColumn('supplier', function ($row) {
                        return $row->supplier->name ?? '-';
                    })
                    ->addColumn('etd', function ($row) {
                        return $row->etd ? $row->etd->format('d-M-Y') : '-';
                    })
                    ->addColumn('eta', function ($row) {
                        return $row->eta ? $row->eta->format('d-M-Y') : '-';
                    })
                    ->addColumn('status', function ($row) {
                        $classStatus = match ($row->status->name ?? '') {
                            'Pending' => 'badge badge-warning',
                            'Completed' => 'badge badge-success',
                            'Rejected' => 'badge badge-danger',
                            'On The Way' => 'badge badge-info',
                            default => 'badge badge-secondary',
                        };
                        return '<span class="' . $classStatus . '">' . ($row->status->name ?? '-') . '</span>';
                    })
                    ->addColumn('updated_at', function ($row) {
                        return $row->updated_at ? $row->updated_at->format('d-M-Y H:i:s') : '-';
                    })
                    ->addColumn('action', function ($row) {
                        $buttons = '<a href="' . route('shipments.show', $row->id) . '" class="btn btn-sm btn-info">Detail</a>';

                        if (auth()->user()->can('shipment.edit')) {
                            $buttons .= ' <a href="' . route('shipments.edit', $row->id) . '" class="btn btn-sm btn-primary ms-1">Edit</a>';
                        }

                        return $buttons;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('pages.shipment.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data Shipment: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $departments = $this->departmentService->getAll();
            $suppliers = $this->supplierService->getAll();
            $statuses = $this->statusService->getAll();
            $items = $this->itemService->getAll();
            return view('pages.shipment.create', compact('suppliers', 'statuses', 'departments', 'items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Form Create Shipment: ' . $e->getMessage());
        }
    }

    public function store(StoreShipmentRequest $request)
    {
        try {
            $data = $request->only([
                'po',
                'no_invoice',
                'no_bl',
                'supplier_id',
                'department_id',
                'etd',
                'eta',
                'notes',
            ]);
            $items = $this->resolveItems($request);
            $this->shipmentService->create($data, $items);
            return redirect()
                ->route('shipments.index')
                ->with('success', 'Shipment berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menyimpan Shipment: ' . $e->getMessage())->withInput();
        }
    }

    public function show(int $id)
    {
        try {
            $shipment = $this->shipmentService->findWithRelations($id);
            return view('pages.shipment.show', compact('shipment'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Detail Shipment: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $shipment = $this->shipmentService->findWithRelations($id);
            $departments = $this->departmentService->getAll();
            $suppliers = $this->supplierService->getAll();
            $statuses = $this->statusService->getAll();
            $items = $this->itemService->getAll();
            return view('pages.shipment.edit', compact('shipment', 'suppliers', 'statuses', 'departments', 'items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Form Edit Shipment: ' . $e->getMessage());
        }
    }

    public function update(UpdateShipmentRequest $request, int $id)
    {
        try {
            $data = $request->only([
                'po',
                'no_invoice',
                'no_bl',
                'supplier_id',
                'department_id',
                'etd',
                'eta',
                'notes',
                'status_id',
                'status_notes',
            ]);

            // Jika ada status_notes, gunakan sebagai catatan snapshot history
            if (!empty($data['status_notes'])) {
                $data['notes'] = $data['status_notes'];
            }
            unset($data['status_notes']);

            // Jika status_id tidak dikirim (user tidak punya permission),
            // hapus dari data agar status lama tidak ter-override
            if (empty($data['status_id'])) {
                unset($data['status_id']);
            }

            $items = $this->resolveItems($request);

            $changedBy = auth()->user()->id;

            $this->shipmentService->update($id, $data, $items, $changedBy);

            return redirect()
                ->route('shipments.show', $id)
                ->with('success', 'Shipment berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Mengupdate Shipment: ' . $e->getMessage())->withInput();
        }
    }

public function history(int $shipment, int $history)
{
    try {
        $history = $this->shipmentService->getHistory($shipment, $history);
        $shipment = $this->shipmentService->findWithRelations($shipment);
        return view('pages.shipment.viewDetailHistory', compact('history', 'shipment'));
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal Memuat History: ' . $e->getMessage());
    }
}



    // ── Resolve item_id + handle "other" via firstOrCreate ────────
    private function resolveItems(FormRequest $request): array
    {
        $itemIds      = $request->input('item_id', []);
        $newItemNames = $request->input('new_item_name', []);
        $quantities   = $request->input('quantity', []);
        $uoms         = $request->input('uom', []);
        $notes        = $request->input('item_notes', []);

        return collect($itemIds)
            ->map(function ($itemId, $index) use ($newItemNames, $quantities, $uoms, $notes) {
                if ($itemId === 'other') {
                    $name = $newItemNames[$index] ?? null;

                    // Guard: jika nama kosong, skip item ini
                    if (empty($name)) {
                        return null;
                    }

                    $itemId = $this->itemService
                        ->firstOrCreateByName($name, $uoms[$index] ?? null)
                        ->id;
                }

                return [
                    'item_id'  => $itemId,
                    'quantity' => $quantities[$index] ?? null,
                    'uom'      => $uoms[$index] ?? null,
                    'notes'    => $notes[$index] ?? null,
                ];
            })
            ->filter()                          // buang null (item dengan nama kosong)
            ->unique('item_id')                 // buang duplicate item_id
            ->values()                          // reset index
            ->toArray();
    }

    private function generateItemCode(string $name): string
    {
        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 6));
        $suffix = strtoupper(substr(md5($name . microtime(true) . uniqid()), 0, 4));

        // Pastikan unik di database
        $code = $base . '-' . $suffix;
        while (Item::where('code', $code)->exists()) {
            $suffix = strtoupper(substr(md5(uniqid()), 0, 4));
            $code = $base . '-' . $suffix;
        }

        return $code;
    }
}
