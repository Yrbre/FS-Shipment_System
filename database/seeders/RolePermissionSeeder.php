<?php
// database/seeders/RolePermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Master Data
            'master.department.view',
            'master.department.create',
            'master.department.edit',
            'master.department.delete',
            'master.warehouse.view',
            'master.warehouse.create',
            'master.warehouse.edit',
            'master.warehouse.delete',
            'master.supplier.view',
            'master.supplier.create',
            'master.supplier.edit',
            'master.supplier.delete',
            'master.item.view',
            'master.item.create',
            'master.item.edit',
            'master.item.delete',
            'master.status.view',
            'master.status.create',
            'master.status.edit',
            'master.status.delete',
            'master.user.view',
            'master.user.create',
            'master.user.edit',
            'master.user.delete',
            'master.role.view',
            'master.role.create',
            'master.role.edit',
            'master.role.delete',

            // Shipment
            'shipment.view',
            'shipment.create',
            'shipment.edit',
            'shipment.delete',

            // IMC
            'imc.view',
            'imc.verify',

            // Tracking
            'tracking.view',

            // Master
            'master',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Admin — full access
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        // Import
        $import = Role::firstOrCreate(['name' => 'Import']);
        $import->givePermissionTo([
            'shipment.view',
            'shipment.create',
            'shipment.edit',
        ]);

        // Buyer
        $buyer = Role::firstOrCreate(['name' => 'Buyer']);
        $buyer->givePermissionTo([
            'shipment.view',
            'shipment.create',
            'shipment.edit',
        ]);

        // User Department
        $user = Role::firstOrCreate(['name' => 'User']);
        $user->givePermissionTo(['tracking.view']);
    }
}
