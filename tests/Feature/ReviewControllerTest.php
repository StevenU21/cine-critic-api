<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{

    public function test_review_content_is_required()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'rating' => 5,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_review_content_must_be_a_string()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'content' => 123,
            'rating' => 5,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_review_content_must_be_at_least_10_characters()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'content' => 'short',
            'rating' => 5,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_review_content_must_not_exceed_1000_characters()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'content' => str_repeat('a', 1001),
            'rating' => 5,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_review_rating_is_required()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'content' => 'This is a great movie!',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_review_rating_must_be_a_number()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'content' => 'This is a great movie!',
            'rating' => 'five',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_review_rating_must_be_at_least_1()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'content' => 'This is a great movie!',
            'rating' => 0,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_review_rating_must_not_exceed_5()
    {
        $user = User::factory()->create();

        $movie = Movie::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'content' => 'This is a great movie!',
            'rating' => 6,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(302);
    }

    public function test_admin_user_can_view_general_review_list()
    {
        Review::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/reviews');

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_general_review_list()
    {
        Review::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/reviews');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_general_review_list()
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

    public function test_admin_user_can_view_general_specific_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/$review->id");

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_general_specific_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/$review->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_general_specific_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/$review->id");

        $response->assertStatus(200);
    }

    public function test_guest_user_cannot_view_general_specific_review()
    {
        $review = Review::factory()->create();

        $response = $this->get("/api/reviews/$review->id");

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_movie_review_list()
    {
        $movie = Movie::factory()->create();

        Review::factory(10)->create([
            'movie_id' => $movie->id,
        ]);

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_movie_review_list()
    {
        $movie = Movie::factory()->create();

        Review::factory(10)->create([
            'movie_id' => $movie->id,
        ]);

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_movie_review_list()
    {
        $movie = Movie::factory()->create();

        Review::factory(10)->create([
            'movie_id' => $movie->id,
        ]);

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/reviews/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_admin_user_can_create_review()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $movie = Movie::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'movie_id' => $movie->id,
            'rating' => 5,
            'content' => 'This is a great movie!',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'movie_id' => $movie->id,
            'user_id' => $user->id,
            'rating' => 5,
            'content' => 'This is a great movie!',
        ]);
    }

    public function test_moderator_user_cant_create_review()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $movie = Movie::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'movie_id' => $movie->id,
            'rating' => 5,
            'content' => 'This is a great movie!',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(403);
    }

    public function test_reviewer_user_can_create_review()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $movie = Movie::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'movie_id' => $movie->id,
            'rating' => 5,
            'content' => 'This is a great movie!',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'movie_id' => $movie->id,
            'user_id' => $user->id,
            'rating' => 5,
            'content' => 'This is a great movie!',
        ]);
    }

    public function test_guest_user_cannot_create_review()
    {
        $movie = Movie::factory()->create();

        $data = [
            'movie_id' => $movie->id,
            'rating' => 5,
            'content' => 'This is a great movie!',
        ];

        $response = $this->post('/api/reviews/movies/' . $movie->id, $data);

        $response->assertStatus(401);
    }

    public function test_admin_user_can_update_review()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $review = Review::factory()->create([
            'movie_id' => $movie->id,
            'user_id' => $user->id,
        ]);

        $updatedData = [
            'content' => 'Updated review content',
            'rating' => 5,
            'user_id' => $user->id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/reviews/movies/$movie->id/$review->id", $updatedData);

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_update_review()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $review = Review::factory()->create([
            'movie_id' => $movie->id,
            'user_id' => $user->id,
        ]);

        $updatedData = [
            'content' => 'Updated review content',
            'rating' => 5,
            'user_id' => $user->id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/reviews/movies/$movie->id/$review->id", $updatedData);

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_update_his_own_review()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $review = Review::factory()->create([
            'movie_id' => $movie->id,
            'user_id' => $user->id,
        ]);

        $updatedData = [
            'content' => 'Updated review content',
            'rating' => 5,
            'user_id' => $user->id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/reviews/movies/$movie->id/$review->id", $updatedData);

        $response->assertStatus(200);
    }

    public function test_reviewer_user_cannot_update_other_user_review()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $review = Review::factory()->create([
            'movie_id' => $movie->id,
        ]);

        $updatedData = [
            'content' => 'Updated review content',
            'rating' => 5,
            'user_id' => $user->id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/reviews/movies/$movie->id/$review->id", $updatedData);

        $response->assertStatus(403);
    }

    public function test_guest_user_cannot_update_review()
    {
        $movie = Movie::factory()->create();

        $review = Review::factory()->create([
            'movie_id' => $movie->id,
        ]);

        $updatedData = [
            'content' => 'Updated review content',
            'rating' => 5,
        ];

        $response = $this->put("/api/reviews/movies/$movie->id/$review->id", $updatedData);

        $response->assertStatus(401);
    }

    public function test_admin_user_can_delete_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/reviews/$review->id");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_moderator_user_can_delete_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/reviews/$review->id");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_reviewer_user_can_delete_his_own_review()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $review = Review::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/reviews/$review->id");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_guest_user_cannot_delete_review()
    {
        $review = Review::factory()->create();

        $response = $this->delete("/api/reviews/$review->id");

        $response->assertStatus(401);
    }
}
