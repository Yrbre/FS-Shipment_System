<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreDepartmentRequest;
use App\Http\Requests\Master\UpdateDepartmentRequest;
use App\Services\MasterData\DepartmentService;


class DepartmentController extends Controller
{
    private DepartmentService $departmentService;

    public function __construct(
        DepartmentService $departmentService
    ) {
        $this->departmentService = $departmentService;
        $this->middleware('permission:master.department.view')->only(['index']);
        $this->middleware('permission:master.department.create')->only(['create', 'store']);
        $this->middleware('permission:master.department.edit')->only(['edit', 'update']);
        $this->middleware('permission:master.department.delete')->only(['destroy']);
    }

    public function index()
    {
        try {
            $departments = $this->departmentService->getAll();
            return view('master.department.index', compact('departments'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data Departemen: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('master.department.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        try {
            $this->departmentService->create($request->validated());
            return redirect()->route('master.departments.index')->with('success', 'Departemen berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menambahkan Departemen: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $department = $this->departmentService->getById($id);
            return view('master.department.edit', compact('department'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data Departemen: ' . $e->getMessage());
        }
    }

    public function update(UpdateDepartmentRequest $request, int $id)
    {
        try {
            $this->departmentService->update($id, $request->validated());
            return redirect()->route('master.departments.index')->with('success', 'Departemen berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memperbarui Departemen: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->departmentService->delete($id);
            return redirect()->route('master.departments.index')->with('success', 'Departemen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menghapus Departemen: ' . $e->getMessage());
        }
    }
}
