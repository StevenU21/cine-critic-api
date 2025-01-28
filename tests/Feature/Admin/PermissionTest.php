<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;

class PermissionTest extends TestCase
{
    public function test_admin_user_can_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/permissions');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/permissions');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/permissions');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_role_list()
    {
        $response = $this->get('/api/admin/permissions');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_user_permissions()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/admin/permissions/{$user->id}/list-permission");

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_user_permissions()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/admin/permissions/{$user->id}/list-permission");

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_user_permissions()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/admin/permissions/{$user->id}/list-permission");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_user_permissions()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/admin/permissions/{$user->id}/list-permission");

        $response->assertStatus(401);
    }

    public function test_admin_user_can_assign_permission_to_user()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $token = $admin->createToken('auth_token')->plainTextToken;

        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer $token")->post("/api/admin/permissions/{$user->id}/give-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(200);

        // Verify that the user has the assigned permissions
        $this->assertTrue($user->hasPermissionTo('create genres'));
        $this->assertTrue($user->hasPermissionTo('update genres'));
    }

    public function test_moderator_user_cant_assign_permission_to_user()
    {
        $moderator = User::factory()->create();

        $moderator->assignRole('moderator');

        $token = $moderator->createToken('auth_token')->plainTextToken;

        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer $token")->post("/api/admin/permissions/{$user->id}/give-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(403);

        // Verify that the user does not have the assigned permissions
        $this->assertFalse($user->hasPermissionTo('create genres'));
        $this->assertFalse($user->hasPermissionTo('update genres'));
    }

    public function test_reviewer_user_cant_assign_permission_to_user()
    {
        $reviewer = User::factory()->create();

        $reviewer->assignRole('reviewer');

        $token = $reviewer->createToken('auth_token')->plainTextToken;

        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer $token")->post("/api/admin/permissions/{$user->id}/give-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(403);

        // Verify that the user does not have the assigned permissions
        $this->assertFalse($user->hasPermissionTo('create genres'));
        $this->assertFalse($user->hasPermissionTo('update genres'));
    }

    public function test_unauthenticated_user_cant_assign_permission_to_user()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->post("/api/admin/permissions/{$user->id}/give-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(401);

        // Verify that the user does not have the assigned permissions
        $this->assertFalse($user->hasPermissionTo('create genres'));
        $this->assertFalse($user->hasPermissionTo('update genres'));
    }

    public function test_admin_user_can_revoke_permission_from_user()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $token = $admin->createToken('auth_token')->plainTextToken;

        $user = User::factory()->create();
        $user->assignRole('reviewer');
        $user->givePermissionTo('create genres');
        $user->givePermissionTo('update genres');

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/admin/permissions/{$user->id}/revoke-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(200);

        // Verify that the user does not have the assigned permissions
        $this->assertFalse($user->hasPermissionTo('create genres'));
        $this->assertFalse($user->hasPermissionTo('update genres'));
    }

    public function test_moderator_user_cant_revoke_permission_from_user()
    {
        $moderator = User::factory()->create();

        $moderator->assignRole('moderator');

        $token = $moderator->createToken('auth_token')->plainTextToken;

        $user = User::factory()->create();
        $user->assignRole('reviewer');
        $user->givePermissionTo('create genres');
        $user->givePermissionTo('update genres');

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/admin/permissions/{$user->id}/revoke-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(403);

        // Verify that the user still has the assigned permissions
        $this->assertTrue($user->hasPermissionTo('create genres'));
        $this->assertTrue($user->hasPermissionTo('update genres'));
    }

    public function test_reviewer_user_cant_revoke_permission_from_user()
    {
        $reviewer = User::factory()->create();

        $reviewer->assignRole('reviewer');

        $token = $reviewer->createToken('auth_token')->plainTextToken;

        $user = User::factory()->create();
        $user->assignRole('reviewer');
        $user->givePermissionTo('create genres');
        $user->givePermissionTo('update genres');

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/admin/permissions/{$user->id}/revoke-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(403);

        // Verify that the user still has the assigned permissions
        $this->assertTrue($user->hasPermissionTo('create genres'));
        $this->assertTrue($user->hasPermissionTo('update genres'));
    }

    public function test_unauthenticated_user_cant_revoke_permission_from_user()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');
        $user->givePermissionTo('create genres');
        $user->givePermissionTo('update genres');

        $response = $this->delete("/api/admin/permissions/{$user->id}/revoke-permission", [
            'permission' => ['create genres', 'update genres'],
        ]);

        $response->assertStatus(401);

        // Verify that the user still has the assigned permissions
        $this->assertTrue($user->hasPermissionTo('create genres'));
        $this->assertTrue($user->hasPermissionTo('update genres'));
    }
}
