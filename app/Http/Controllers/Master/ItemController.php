<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreItemRequest;
use App\Http\Requests\Master\UpdateItemRequest;
use App\Services\MasterData\ItemService;


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

    public function index()
    {
        try {
            $items = $this->itemService->getAll();
            return view('master.item.index', compact('items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load items: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('master.item.create');
    }

    public function store(StoreItemRequest $request)
    {
        try {
            $this->itemService->create($request->validated());
            return redirect()->route('master.items.index')->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create item: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $item = $this->itemService->getById($id);
            return view('master.item.edit', compact('item'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load item: ' . $e->getMessage());
        }
    }

    public function update(UpdateItemRequest $request, int $id)
    {
        try {
            $this->itemService->update($id, $request->validated());
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
