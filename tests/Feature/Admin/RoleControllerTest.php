<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    public function test_admin_user_can_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/roles');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/roles');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/roles');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_role_list()
    {
        $response = $this->get('/api/admin/roles');

        $response->assertStatus(401);
    }

    public function test_admin_list_should_return_all_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/roles');

        $response->assertJson([
            1 => 'admin',
            2 => 'moderator',
            3 => 'reviewer',
        ]);
    }

    public function test_admin_can_assign_existing_role_to_user()
    {
        // create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        // create a user
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $roleModerator = Role::where('name', 'moderator')->first();
        $roleReviewer = Role::where('name', 'reviewer')->first();

        $this->assertNotNull($roleModerator);
        $this->assertNotNull($roleReviewer);

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/admin/roles/{$user->id}/assign-role", [
            'role' => 'moderator',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Role assigned successfully']);

        $this->assertTrue($user->fresh()->hasRole('moderator'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('reviewer'));
    }

    public function test_admin_cant_assign_multiples_roles_to_user()
    {
        // create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        // create a user
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $roleModerator = Role::where('name', 'moderator')->first();
        $roleReviewer = Role::where('name', 'reviewer')->first();

        $this->assertNotNull($roleModerator);
        $this->assertNotNull($roleReviewer);

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/admin/roles/{$user->id}/assign-role", [
            'role' => ['moderator', 'reviewer'],
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Only one role can be assigned at a time']);

        $this->assertTrue($user->hasRole('reviewer'));
    }

    public function test_admin_cant_assign_non_existing_role_to_user()
    {
        // create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        // create a user
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/admin/roles/{$user->id}/assign-role", [
            'role' => 'non-existing-role',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('role');

        $this->assertFalse($user->fresh()->hasRole('non-existing-role'));
    }

    public function test_moderator_cant_assign_role_to_user()
    {
        // create a moderator user
        $moderator = User::factory()->create();
        $moderator->assignRole('moderator');
        $token = $moderator->createToken('auth_token')->plainTextToken;

        // create a user
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/admin/roles/{$user->id}/assign-role", [
            'role' => 'moderator',
        ]);

        $response->assertStatus(403);

        $this->assertFalse($user->fresh()->hasRole('moderator'));
    }

    public function test_reviewer_cant_assign_role_to_user()
    {
        // create a reviewer user
        $reviewer = User::factory()->create();
        $reviewer->assignRole('reviewer');
        $token = $reviewer->createToken('auth_token')->plainTextToken;

        // create a user
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/admin/roles/{$user->id}/assign-role", [
            'role' => 'moderator',
        ]);

        $response->assertStatus(403);

        $this->assertFalse($user->fresh()->hasRole('moderator'));
    }

    public function test_unauthenticated_user_cant_assign_role_to_user()
    {
        // create a user
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->putJson("/api/admin/roles/{$user->id}/assign-role", [
            'role' => 'moderator',
        ]);

        $response->assertStatus(401);

        $this->assertFalse($user->fresh()->hasRole('moderator'));
    }

    public function test_assign_role_endpoint_should_return_user_and_role()
    {
        // create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        // create a user
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/admin/roles/{$user->id}/assign-role", [
            'role' => 'moderator',
        ]);

        $response->assertStatus(200);
    }
}
