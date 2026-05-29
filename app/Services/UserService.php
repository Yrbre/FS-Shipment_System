<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll()
    {
        return User::with('roles', 'department')->get();
    }

    public function getById(int $id)
    {
        return User::with('roles', 'department')->findOrFail($id);
    }

    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'department_id' => $data['department_id'] ?? null,
        ]);

        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);

        $updateData = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'department_id' => $data['department_id'] ?? $user->department_id,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (!empty($data['role'])) {
            $user->syncRoles($data['role']);
        }

        return $user;
    }

    public function delete(int $id)
    {
        return User::findOrFail($id)->delete();
    }
}
