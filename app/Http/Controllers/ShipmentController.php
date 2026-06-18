<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateShipmentRequest;
use App\Models\Item;
use App\Models\Supplier;
use App\Services\HscodeDataService;
use App\Services\MasterData\DepartmentService;
use App\Services\MasterData\ItemService;
use App\Services\MasterData\StatusService;
use App\Services\MasterData\SupplierService;
use App\Services\ShipmentService;
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
                if ($user->hasAnyRole(['Admin', 'Purchasing', 'IMC', 'Buyer'])) {
                    $shipments = $this->shipmentService->getAll();
                } else {
                    $shipments = $this->shipmentService->getByDepartment($user->department_id);
                }

                if ($request->filled('status')) {
                    $shipments = $shipments->filter(function ($row) use ($request) {
                        return strtolower($row->status->name ?? '') === strtolower($request->status);
                    });
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
                            'Delivered' => 'badge badge-success',
                            'Rejected' => 'badge badge-danger',
                            'Process' => 'badge badge-info',
                            default => 'badge badge-secondary',
                        };
                        return '<span class="' . $classStatus . '">' . ($row->status->name ?? '-') . '</span>';
                    })
                    ->addColumn('updated_at', function ($row) {
                        return $row->updated_at ? $row->updated_at->format('d-M-Y H:i:s') : '-';
                    })
                    ->addColumn('action', function ($row) {
                        $buttons = '<a href="' . route('shipments.show', $row->id) . '" class="btn btn-sm btn-info">Detail</a>';

                        if (auth()->user()->can('shipment.edit') && now() < $row->etd) {
                            $buttons .= ' <a href="' . route('shipments.edit', $row->id) . '" class="btn btn-sm btn-primary ms-1">Edit</a>';
                        } elseif (auth()->user()->hasAnyRole(['Admin', 'Purchasing'])) {
                            $buttons .= ' <a href="' . route('shipments.edit', $row->id) . '" class="btn btn-sm btn-primary ms-1">Edit</a>';
                        }

                        return $buttons;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('pages.shipment.index', [
                'statusFilter' => $request->get('status'),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data Shipment: ' . $e->getMessage());
        }
    }

    public function create(HscodeDataService $dataService)
    {
        try {
            $departments = $this->departmentService->getAll();
            $suppliers = $this->supplierService->getAll();
            $statuses = $this->statusService->getAll();
            $items =   $dataService->getAll();
            return view('pages.shipment.create', compact('suppliers', 'statuses', 'departments', 'items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Form Create Shipment: ' . $e->getMessage());
        }
    }

    public function store(StoreShipmentRequest $request)
    {
        try {
            $uom = $request->input('uom');
            $data['uom'] = strtoupper(is_array($uom) ? implode(',', $uom) : $uom);
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

            if ($data['supplier_id'] === 'other') {
                $newSupplierName    = strtoupper(trim($request->input('new_supplier_name', '')));
                $supplier           = \App\Models\Supplier::firstOrCreate(['name' => $newSupplierName]);
                $data['supplier_id'] = $supplier->id;
            }

            $items = $this->resolveItems($request);
            $this->shipmentService->create($data, $items);

            return redirect()
                ->route('shipments.index')
                ->with('success', 'Shipment berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal Menyimpan Shipment: ' . $e->getMessage())
                ->withInput();
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

    public function edit(int $id, HscodeDataService $dataService)
    {
        try {
            $shipment = $this->shipmentService->findWithRelations($id);
            $departments = $this->departmentService->getAll();
            $suppliers = $this->supplierService->getAll();
            $statuses = $this->statusService->getAll();
            $items =   $dataService->getAll();
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

            // Resolve "Other" supplier -> buat / pakai supplier yang sudah ada
            if ($data['supplier_id'] === 'other') {
                $supplier = Supplier::firstOrCreate([
                    'name' => trim($request->input('new_supplier_name')),
                ]);
                $data['supplier_id'] = $supplier->id;
            }

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

    public function history(int $id)
    {
        try {
            $shipment = $this->shipmentService->findWithRelations($id);

            $historiesSorted = $shipment->histories->values();

            $historyDiffs = [];

            foreach ($historiesSorted as $idx => $history) {
                $prev = $idx > 0 ? $historiesSorted[$idx - 1] : null;

                $shipmentFields = ['po', 'no_invoice', 'no_bl', 'supplier_id', 'status_id', 'etd', 'eta'];
                $changedFields  = [];

                if ($prev) {
                    foreach ($shipmentFields as $field) {
                        $currVal = $this->normalizeValue($field, $history->{$field} ?? '');
                        $prevVal = $this->normalizeValue($field, $prev->{$field} ?? '');

                        if ($currVal !== $prevVal) {
                            $changedFields[] = $field;
                        }
                    }
                }


                $prevItems      = $prev ? $prev->items->keyBy('item_id') : collect();
                $currItems      = $history->items->keyBy('item_id');
                $addedItemIds   = $currItems->keys()->diff($prevItems->keys())->toArray();
                $removedItemIds = $prevItems->keys()->diff($currItems->keys())->toArray();

                $changedItemFields = [];
                foreach ($currItems as $itemId => $hItem) {
                    $prevItem = $prevItems->get($itemId);
                    if (!$prevItem || in_array($itemId, $addedItemIds)) continue;

                    foreach (['item_id', 'hscode', 'quantity', 'uom', 'notes'] as $field) {
                        if ((string) ($prevItem->{$field} ?? '') !== (string) ($hItem->{$field} ?? '')) {
                            $changedItemFields[$itemId][] = $field;
                        }
                    }
                }

                $historyDiffs[$history->id] = [
                    'prev'              => $prev,
                    'changedFields'     => $changedFields,
                    'addedItemIds'      => $addedItemIds,
                    'removedItemIds'    => $removedItemIds,
                    'changedItemFields' => $changedItemFields,
                    'prevItems'         => $prevItems,
                ];
            }

            return view('pages.shipment.viewDetailHistory', compact('shipment', 'historyDiffs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Detail Shipment: ' . $e->getMessage());
        }
    }

    private function normalizeValue(string $field, $value): string
    {
        if (empty($value)) return '';

        $dateFields = ['etd', 'eta'];
        if (in_array($field, $dateFields)) {
            try {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return (string) $value;
            }
        }

        return (string) $value;
    }



    // ── Resolve item_id + handle "other" via firstOrCreate ────────
    private function resolveItems(Request $request): array
    {
        $rfs          = $request->input('rf', []);
        $itemNames    = $request->input('item_name', []);
        $hscodes      = $request->input('hscode', []);
        $newItemNames = $request->input('new_item_name', []);
        $quantities   = $request->input('quantity', []);
        $uoms         = $request->input('uom', []);
        $notes        = $request->input('item_notes', []);

        return collect($rfs)
            ->map(function ($rf, $index) use ($itemNames, $hscodes, $newItemNames, $quantities, $uoms, $notes) {

                $uom = $uoms[$index] ?? null;

                // Other (New Item) — rf kosong
                if (empty($rf)) {
                    $newName = $newItemNames[$index] ?? null;

                    if (empty($newName)) return null;

                    return [
                        'rf'       => null,
                        'item_name' => $newName,
                        'hscode'   => null,
                        'quantity' => $quantities[$index] ?? null,
                        'uom'      => $uom,
                        'notes'    => $notes[$index] ?? null,
                    ];
                }

                // Item dari API
                return [
                    'rf'        => $rf,
                    'item_name' => $itemNames[$index] ?? null,
                    'hscode'    => $hscodes[$index] ?? null,
                    'quantity'  => $quantities[$index] ?? null,
                    'uom'       => $uom,
                    'notes'     => $notes[$index] ?? null,
                ];
            })
            ->filter()
            ->values()
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
