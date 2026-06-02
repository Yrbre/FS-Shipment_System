<?php
// database/seeders/AdminUserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@tifico.co.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('1'),
            ]
        );
        $admin->assignRole('Admin');
    }
}
