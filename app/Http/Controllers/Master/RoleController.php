<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\MasterData\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    private RoleService $roleService;

    public function __construct(
        RoleService $roleService
    ) {
        $this->roleService = $roleService;
        $this->middleware('permission:master.role.view')->only(['index']);
        $this->middleware('permission:master.role.create')->only(['create', 'store']);
        $this->middleware('permission:master.role.edit')->only(['edit', 'update']);
        $this->middleware('permission:master.role.delete')->only(['destroy']);
    }

    public function index()
    {
        try {
            $roles = $this->roleService->getAll();
            return view('master.role.index', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            // Ambil prefix: "master.department" → "master.department", "shipment" → "shipment"
            if (count($parts) >= 3) {
                return $parts[0] . '.' . $parts[1]; // master.department, master.item, dst
            }
            return $parts[0]; // shipment, tracking, imc
        });
        return view('master.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('master.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            if (count($parts) >= 3) {
                return $parts[0] . '.' . $parts[1];
            }
            return $parts[0];
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('master.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'          => 'required|string|max:100|unique:roles,name,' . $role->id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('master.roles.index')->with('success', 'Role berhasil diupdate.');
    }

    public function destroy(int $id)
    {
        try {
            $this->roleService->delete($id);
            return redirect()->route('master.roles.index')->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
