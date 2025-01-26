<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    public function test_admin_user_can_view_review_list()
    {
        Review::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/reviews');

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_review_list()
    {
        Review::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/reviews');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_review_list()
    {
        Review::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/reviews');

        $response->assertStatus(200);
    }

    public function test_guest_user_cannot_view_review_list()
    {
        $response = $this->get('/api/reviews');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_specific_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/$review->id");

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_specific_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/$review->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_specific_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/$review->id");

        $response->assertStatus(200);
    }

    public function test_guest_user_cannot_view_specific_review()
    {
        $review = Review::factory()->create();

        $response = $this->get("/api/reviews/$review->id");

        $response->assertStatus(401);
    }
}
