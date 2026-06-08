<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreSupplierRequest;
use App\Http\Requests\Master\UpdateSupplierRequest;
use App\Services\MasterData\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private SupplierService $supplierService;
    public function __construct(
        SupplierService $supplierService
    ) {
        $this->supplierService = $supplierService;
        $this->middleware('permission:master.supplier.view')->only(['index']);
        $this->middleware('permission:master.supplier.create')->only(['create', 'store']);
        $this->middleware('permission:master.supplier.edit')->only(['edit', 'update']);
        $this->middleware('permission:master.supplier.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $suppliers = $this->supplierService->getAll();
                return datatables()->of($suppliers)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($row) {
                        return \Carbon\Carbon::parse($row->created_at)->format('d-M-Y');
                    })
                    ->addColumn('action', function ($row) {
                        return '
                        <a href="' . route('master.suppliers.edit', $row->id) . '"
                        class="btn btn-sm btn-warning">Edit</a>

                            <button class="btn btn-sm btn-danger js-delete"
                            data-name="' . $row->name . '"
                            data-url="' . route('master.suppliers.destroy', $row->id) . '">
                            Hapus
                            </button>
                    ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('master.supplier.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('master.supplier.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        try{
            $this->supplierService->create($request->validated());
            return redirect()->route('master.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Menambahkan Data: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try{
            $supplier = $this->supplierService->getById($id);
            return view('master.supplier.edit', compact('supplier'));
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Memuat Data: ' . $e->getMessage());
        }
    }

    public function update(UpdateSupplierRequest $request, int $id)
    {
        try{
            $this->supplierService->update($id, $request->validated());
            return redirect()->route('master.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Memperbarui Data: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->supplierService->delete($id);
            return redirect()->route('master.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Menghapus Data: ' . $e->getMessage());
        }
    }
}
