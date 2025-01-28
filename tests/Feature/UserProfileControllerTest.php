<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    public function test_admin_can_view_user_profile()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/user-profile');

        $response->assertStatus(200);
    }

    public function test_moderator_can_view_user_profile()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/user-profile');

        $response->assertStatus(200);
    }

    public function test_user_can_view_user_profile()
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/user-profile');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_user_profile()
    {
        $response = $this->get('/api/user-profile');

        $response->assertStatus(401);
    }
}
