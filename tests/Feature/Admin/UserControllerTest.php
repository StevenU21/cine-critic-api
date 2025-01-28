<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    public function test_admin_user_can_view_user_list()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_user_list()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_user_list()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cant_view_user_list()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(401);
    }

    public function test_admin_can_view_specific_user()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/users/$user->id");

        $response->assertStatus(200);
    }

    public function test_moderator_can_view_specific_user()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/users/$user->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_can_view_specific_user()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/users/$user->id");

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cant_view_specific_user()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/users/$user->id");

        $response->assertStatus(401);
    }
}
