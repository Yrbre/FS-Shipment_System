<?php

namespace App\Services\MasterData;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->userRepository->getById($id);
    }

    public function create(array $data)
    {
        try{
            DB::Transaction(function() use($data){
                $data['password'] = Hash::make($data['password']);
                $user = $this->userRepository->create([
                    'name'          => $data['name'],
                    'email'         => $data['email'],
                    'password'      => $data['password'],
                    'department_id' => $data['department_id']
                ]);
                $role = Role::findById($data['role_id']);
                $user->assignRole($role);

                return $user;
            });
        }catch(\Exception $e){
           redirect()->route('master.users.create')->with('error', 'Service gagal berjalan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(int $id, array $data)
    {
        try{
            DB::Transaction(function() use($id, $data){
                $user = $this->userRepository->getById($id);
                $updateData = [
                    'name'          => $data['name'],
                    'email'         => $data['email'],
                    'department_id' => $data['department_id']
                ];

                if (isset($data['password']) && !empty($data['password'])) {
                    $updateData['password'] = Hash::make($data['password']);
                }

                $this->userRepository->update($id, $updateData);

                // Update role
                if (isset($data['role_id'])) {
                    $role = Role::findById($data['role_id']);
                    $user->syncRoles($role);
                }

                return $user;
            });
        }catch(\Exception $e){
           redirect()->route('master.users.create')->with('error', 'Service gagal berjalan: ' . $e->getMessage())->withInput();
        }
    }

    public function delete(int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function getUserRoles(User $user)
    {
        return $this->userRepository->getRole($user);
    }

    public function getAllRoles()
    {
        return $this->userRepository->getAllRoles();
    }
}
