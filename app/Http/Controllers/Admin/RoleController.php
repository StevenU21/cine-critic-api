<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::pluck('name', 'id');

        return response()->json($roles);
    }

    public function assignRole(Request $request, User $user): JsonResponse
    {
        $this->authorize('assignRole', Role::class);

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $role = $request->input('role');

        $user->syncRoles($role);

        return response()->json([
            'message' => 'Role assigned successfully',
            'user' => $user,
            'role' => $role,
        ]);
    }
}
