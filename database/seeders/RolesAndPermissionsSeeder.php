<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create genre', 'read genre', 'update genre', 'delete genre',
            'create user', 'read user', 'update user', 'delete user',
            'assign role', 'unassign role', 'assign permission', 'unassign permission',
        ];
    }
}
