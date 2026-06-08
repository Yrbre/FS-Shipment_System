<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreStatusRequest;
use App\Http\Requests\Master\UpdateStatusRequest;
use App\Services\MasterData\StatusService;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        try{
            if($request->ajax()){
                $statuses = $this->statusService->getAll();
                return datatables()->of($statuses)
                    ->addIndexColumn()
                    ->addColumn('created_at', function($row){
                        return \Carbon\Carbon::parse($row->created_at)->format('d-M-Y');
                    })
                    ->addColumn('action', function($row){
                        return '
                            <a href="' . route('master.statuses.edit', $row->id) . '"
                            class="btn btn-sm btn-warning">Edit</a>

                            <button class="btn btn-sm btn-danger js-delete"
                            data-name="' . $row->name . '"
                            data-code="' . $row->code . '"
                            data-url="' . route('master.statuses.destroy', $row->id) . '">
                            Hapus
                            </button>
                        ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('master.status.index');
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
