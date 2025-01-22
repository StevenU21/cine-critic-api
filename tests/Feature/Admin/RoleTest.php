<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolesAndPermissionsSeeder;
use App\Models\User;
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
}
