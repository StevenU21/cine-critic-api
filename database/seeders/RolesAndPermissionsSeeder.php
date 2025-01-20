<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'create genre', 'read genre', 'update genre', 'delete genre',
            'create user', 'read user', 'update user', 'delete user',
            'assign role', 'unassign role', 'assign permission', 'unassign permission',
        ];

        // Save permissions to database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $reviewerRole = Role::firstOrCreate(['name' => 'reviewer']);

        //Assign permissions to roles
        $adminRole->givePermissionTo($permissions);

        $moderatorRole->givePermissionTo([
            'create genre', 'read genre', 'update genre', 'delete genre',
            'create user', 'read user', 'update user', 'delete user',
        ]);

        $reviewerRole->givePermissionTo([
            'read genre', 'read user',
        ]);
    }
}
