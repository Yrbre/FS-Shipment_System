<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreItemRequest;
use App\Http\Requests\Master\UpdateItemRequest;
use App\Services\MasterData\ItemService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    private ItemService $itemService;
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
        $this->middleware('permission:master.item.view')->only(['index']);
        $this->middleware('permission:master.item.create')->only(['create', 'store']);
        $this->middleware('permission:master.item.edit')->only(['edit', 'update']);
        $this->middleware('permission:master.item.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $items = $this->itemService->getAll();
                return DataTables::of($items)
                    ->addIndexColumn()
                    ->addColumn(
                        'created_at',
                        fn($row) =>
                        \Carbon\Carbon::parse($row->created_at)->format('d-M-Y')
                    )
                    ->addColumn('action', function ($row) {
                        return '
                        <a href="' . route('master.items.edit', $row->id) . '"
                        class="btn btn-sm btn-warning">Edit</a>

                        <button class="btn btn-sm btn-danger js-delete"
                        data-name="' . $row->name . '"
                        data-code="' . $row->code . '"
                        data-url="' . route('master.items.destroy', $row->id) . '">
                        Hapus
                        </button>
                    ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('master.item.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load items: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $uom = $this->itemService->getAll()->pluck('uom')->unique()
        ->values()->all();
        return view('master.item.create', compact('uom'));
    }

    public function store(StoreItemRequest $request)
    {
        try {
            if ($request->uom === 'other'){
                $uom = $request->other_uom;
            }else{
                $uom = $request->uom;
            }

            $this->itemService->create(
                ['code' => $request->code,
                'name' => $request->name,
                'uom' => $uom,
                'description' => $request->description]
            );

            return redirect()->route('master.items.index')->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create item: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $item = $this->itemService->getById($id);
            $uom = $this->itemService->getAll()->pluck('uom')->unique()
        ->values()->all();
            return view('master.item.edit', compact('item', 'uom'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load item: ' . $e->getMessage());
        }
    }

    public function update(UpdateItemRequest $request, int $id)
    {
        try {

            if ($request->uom === 'other'){
                $uom = $request->other_uom;
            }else{
                $uom = $request->uom;
            }

            $this->itemService->update($id, [
                'code' => $request->code,
                'name' => $request->name,
                'uom' => $uom,
                'description' => $request->description
            ]);
            return redirect()->route('master.items.index')->with('success', 'Item updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update item: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->itemService->delete($id);
            return redirect()->route('master.items.index')->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }
}
