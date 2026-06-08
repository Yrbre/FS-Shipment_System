<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreUserRequest;
use App\Http\Requests\Master\UpdateUserRequest;
use App\Services\MasterData\DepartmentService;
use App\Services\MasterData\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    private DepartmentService $departmentService;
    public function __construct(
        UserService $userService,
        DepartmentService $departmentService
    ) {
        $this->userService = $userService;
        $this->departmentService = $departmentService;
        $this->middleware('permission:master.user.view')->only(['index']);
        $this->middleware('permission:master.user.create')->only(['create', 'store']);
        $this->middleware('permission:master.user.edit')->only(['edit', 'update']);
        $this->middleware('permission:master.user.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $users = $this->userService->getAll();
                return datatables()->of($users)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($user) {
                        return $user->created_at->format('d-M-Y');
                    })
                    ->addColumn('role', function ($user) {
                        return $this->userService->getUserRoles($user);
                    })
                    ->addColumn('action', function ($row) {
                        return '
                    <a href="' . route('master.users.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>
                    <button class="btn btn-sm btn-danger js-delete"
                    data-name="' . $row->name . '"
                    data-role="' . $this->userService->getUserRoles($row) . '"
                    data-url="' . route('master.users.destroy', $row->id) . '">
                    Hapus
                    </button>
                    ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('master.user.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data pengguna: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $departments    = $this->departmentService->getAll();
        $roles          = $this->userService->getAllRoles();
        return view('master.user.create', compact('roles', 'departments'));
    }

    public function store(StoreUserRequest $request)
    {
        try {

            $this->userService->create($request->validated());
            return redirect()->route('master.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('master.users.create')->with('error', 'Gagal membuat pengguna: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(int $id)
    {
        try {
            $departments    = $this->departmentService->getAll();
            $user = $this->userService->getById($id);
            $roles = $this->userService->getAllRoles();
            return view('master.user.edit', compact('user', 'roles', 'departments'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat pengguna: ' . $e->getMessage());
        }
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            $this->userService->update($id, $request->validated());
            return redirect()->route('master.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('master.users.edit', $id)->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->userService->delete($id);
            return redirect()->route('master.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
