<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage settings',
            'manage users',
            'manage roles',
            'manage products',
            'manage purchases',
            'manage tickets',
            'reply tickets',
            'manage services',
            'manage cms',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Support Staff
        $supportStaff = Role::firstOrCreate(['name' => 'Support Staff']);
        $supportStaff->givePermissionTo([
            'manage products',
            'manage purchases',
            'manage tickets',
            'reply tickets',
            'manage services',
        ]);

        // Content Manager
        $contentManager = Role::firstOrCreate(['name' => 'Content Manager']);
        $contentManager->givePermissionTo([
            'manage cms',
            'manage products',
        ]);
    }
}
