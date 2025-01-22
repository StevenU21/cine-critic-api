<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function list(): JsonResponse
    {
        $permissions = Permission::pluck('name', 'id');

        return response()->json($permissions);
    }

    public function givePermission(Request $request, User $user): JsonResponse
    {
        $permission = Permission::findOrFailCustom($request->input('permission'));

        // Assign the new permissions
        $user->givePermissionTo($permission);

        return response()->json(['message' => 'Permission granted successfully']);
    }

    public function revokePermission(Request $request, User $user): JsonResponse
    {
        $permission = Permission::findOrFailCustom($request->input('permission'));

        // Revoke the permissions
        $user->revokePermissionTo($permission);

        return response()->json(['message' => 'Permission revoked successfully']);
    }
}
