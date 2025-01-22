<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::pluck('name', 'id');

        return response()->json($roles);
    }

    public function assignRole(Request $request, User $user): JsonResponse
    {
        $role = Role::findOrFailCustom($request->input('role'));

        // Remove all current roles
        $user->roles()->detach();

        // Assign the new role
        $user->assignRole($role);

        return response()->json(['message' => 'Role updated successfully']);
    }
}
