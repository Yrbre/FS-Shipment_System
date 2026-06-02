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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Admin — full access
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        // Purchasing
        $purchasing = Role::firstOrCreate(['name' => 'Purchasing']);
        $purchasing->givePermissionTo([
            'shipment.view',
            'shipment.create',
            'shipment.edit',
            'master.supplier.view',
            'master.item.view',
            'master.department.view',
            'master.status.view',
        ]);

        // IMC
        $imc = Role::firstOrCreate(['name' => 'IMC']);
        $imc->givePermissionTo([
            'imc.view',
            'imc.verify',
            'shipment.view',
            'master.warehouse.view',
        ]);

        // User Department
        $user = Role::firstOrCreate(['name' => 'User']);
        $user->givePermissionTo(['tracking.view']);
    }
}
