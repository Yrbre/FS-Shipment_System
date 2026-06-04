<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreStatusRequest;
use App\Http\Requests\Master\UpdateStatusRequest;
use App\Services\MasterData\StatusService;

class StatusController extends Controller
{
    private StatusService $statusService;
    public function __construct(StatusService $statusService)
    {
        $this->statusService = $statusService;
        $this->middleware('permission:master.status.view')->only(['index']);
        $this->middleware('permission:master.status.create')->only(['create', 'store']);
        $this->middleware('permission:master.status.edit')->only(['edit', 'update']);
        $this->middleware('permission:master.status.delete')->only(['destroy']);
    }

    public function index()
    {
        try{
            $statuses = $this->statusService->getAll();
            return view('master.status.index', compact('statuses'));
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Memuat Data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('master.status.create');
    }

    public function store(StoreStatusRequest $request)
    {
        try{
            $this->statusService->create($request->validated());
            return redirect()->route('master.statuses.index')->with('success', 'Status berhasil ditambahkan.');
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Menyimpan Data: ' . $e->getMessage());

        }
    }

    public function edit(int $id)
    {
        try{
            $status = $this->statusService->getById($id);
            return view('master.status.edit', compact('status'));
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Memuat Data: ' . $e->getMessage());
        }
    }

    public function update(UpdateStatusRequest $request, int $id)
    {
        try{
            $this->statusService->update($id, $request->validated());
            return redirect()->route('master.statuses.index')->with('success', 'Status berhasil diperbarui.');
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Memperbarui Data: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->statusService->delete($id);
            return redirect()->route('master.statuses.index')->with('success', 'Status berhasil dihapus.');
        }catch(\Exception $e){
            return redirect()->back()->with('error','Gagal Menghapus Data: ' . $e->getMessage());
        }
    }
}
