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
            'create genres', 'read genres', 'update genres', 'delete genres',
            'create users', 'read users', 'update users', 'delete users',
            'assign roles', 'read roles' , 'assign permissions', 'revoke permissions',
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
            'read genres', 'read users', 'update users', 'delete users',
        ]);

        $reviewerRole->givePermissionTo([
            'read genres', 'read users',
        ]);
    }
}
