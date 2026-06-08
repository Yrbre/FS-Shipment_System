<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreWarehouseRequest;
use App\Http\Requests\Master\UpdateWarehouseRequest;
use App\Services\MasterData\WarehouseService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private WarehouseService $warehouseService;
    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
        $this->middleware('permission:master.warehouse.view')->only(['index']);
        $this->middleware('permission:master.warehouse.create')->only(['create', 'store']);
        $this->middleware('permission:master.warehouse.edit')->only(['edit', 'update']);
        $this->middleware('permission:master.warehouse.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try{
            if ($request->ajax()) {
                $warehouses = $this->warehouseService->getAll();
                return datatables()->of($warehouses)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($warehouse) {
                        return $warehouse->created_at->format('d-M-Y');
                    })
                    ->addColumn('action', function ($row) {
                        return '
                    <a href="' . route('master.warehouses.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>
                    <button class="btn btn-sm btn-danger js-delete"
                    data-name="' . $row->name . '"
                    data-code="' . $row->code . '"
                    data-url="' . route('master.warehouses.destroy', $row->id) . '">
                    Hapus
                    </button>
                    ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('master.warehouse.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data gudang: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('master.warehouse.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        try{
            $this->warehouseService->create($request->validated());
            return redirect()->route('master.warehouses.index')->with('success', 'Warehouse created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat gudang: ' . $e->getMessage());
        }

    }

    public function edit(int $id)
    {
        try{
            $warehouse = $this->warehouseService->getById($id);
            return view('master.warehouse.edit', compact('warehouse'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data gudang: ' . $e->getMessage());
        }
    }

    public function update(UpdateWarehouseRequest $request, int $id)
    {
        try{
            $this->warehouseService->update($id, $request->validated());
            return redirect()->route('master.warehouses.index')->with('success', 'Warehouse updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui gudang: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->warehouseService->delete($id);
            return redirect()->route('master.warehouses.index')->with('success', 'Warehouse deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('master.warehouses.index')->with('error', 'Gagal menghapus gudang: ' . $e->getMessage());
        }
    }
}
