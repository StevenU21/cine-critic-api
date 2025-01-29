<?php

namespace Tests\Feature;

use App\Models\User;
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

    public function test_reviewer_can_view_user_profile()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/user-profile');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_user_profile()
    {
        $response = $this->get('/api/user-profile');

        $response->assertStatus(401);
    }

    public function test_admin_can_view_other_user_profile()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $otherUser = User::factory()->create();

        $otherUser->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/user-profile/$otherUser->id");

        $response->assertStatus(200);
    }

    public function test_moderator_cannot_view_admin_profile()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $otherUser = User::factory()->create();
        $otherUser->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/user-profile/$otherUser->id");

        $response->assertStatus(403);
    }

    public function test_moderator_can_view_other_user_profile()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $otherUser = User::factory()->create();
        $otherUser->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/user-profile/$otherUser->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_cannot_view_admin_profile()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $otherUser = User::factory()->create();
        $otherUser->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/user-profile/$otherUser->id");

        $response->assertStatus(403);
    }

    public function test_reviewer_cannot_view_moderator_profile()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $otherUser = User::factory()->create();
        $otherUser->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/user-profile/$otherUser->id");

        $response->assertStatus(403);
    }

    public function test_reviewer_can_view_other_user_profile()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $otherUser = User::factory()->create();
        $otherUser->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/user-profile/$otherUser->id");

        $response->assertStatus(200);
    }

}
