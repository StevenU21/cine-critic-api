<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolesAndPermissionsSeeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use refreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_user_can_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/roles');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/roles');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_role_list()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/roles');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_role_list()
    {
        $response = $this->get('/api/roles');

        $response->assertStatus(401);
    }

    public function test_roles_list_should_return_all_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/roles');

        $response->assertJson([
            1 => 'admin',
            2 => 'moderator',
            3 => 'reviewer',
        ]);
    }

    public function test_admin_can_assign_existing_role_to_user()
    {
        // Crear un usuario admin
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('auth_token')->plainTextToken;

        // Crear un usuario al que se le asignará un nuevo rol
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $roleModerator = Role::where('name', 'moderator')->first();
        $roleReviewer = Role::where('name', 'reviewer')->first();

        $this->assertNotNull($roleModerator);
        $this->assertNotNull($roleReviewer);

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/roles/{$user->id}/assign-role", [
            'role' => 'moderator',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Role assigned successfully']);

        $this->assertTrue($user->fresh()->hasRole('moderator'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('reviewer'));
    }
}
