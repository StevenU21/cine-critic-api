<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function test_admin_user_can_view_count_list()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/counts');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_count_list()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/counts');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_count_list()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/counts');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_count_list()
    {
        $response = $this->get('/api/admin/dashboard/counts');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_top_rated_movies()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-rated-movies');

        $response->assertStatus(200);
    }

        public function test_admin_user_can_view_top_rated_movies_filter_by_year()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $startDate = '2023-01-01';
        $endDate = '2023-12-31';

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->get('/api/admin/dashboard/top-rated-movies?start_date=' . $startDate . '&end_date=' . $endDate);

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_top_rated_movies()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-rated-movies');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_top_rated_movies()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-rated-movies');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_top_rated_movies()
    {
        $response = $this->get('/api/admin/dashboard/top-rated-movies');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_top_users()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-users');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_top_users()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-users');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_top_users()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-users');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_top_users()
    {
        $response = $this->get('/api/admin/dashboard/top-users');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_top_genres()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-genres');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_top_genres()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-genres');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_top_genres()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-genres');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_top_genres()
    {
        $response = $this->get('/api/admin/dashboard/top-genres');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_top_directors()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-directors');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_top_directors()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-directors');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_top_directors()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/top-directors');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_top_directors()
    {
        $response = $this->get('/api/admin/dashboard/top-directors');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_recent_movies()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-movies');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_recent_movies()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-movies');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_recent_movies()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-movies');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_recent_movies()
    {
        $response = $this->get('/api/admin/dashboard/recent-movies');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_recent_reviews()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-reviews');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_recent_reviews()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-reviews');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_recent_reviews()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-reviews');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_recent_reviews()
    {
        $response = $this->get('/api/admin/dashboard/recent-reviews');

        $response->assertStatus(401);
    }

    public function test_admin_user_can_view_recent_users()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-users');

        $response->assertStatus(200);
    }

    public function test_moderator_user_cant_view_recent_users()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-users');

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_view_recent_users()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/admin/dashboard/recent-users');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cant_view_recent_users()
    {
        $response = $this->get('/api/admin/dashboard/recent-users');

        $response->assertStatus(401);
    }
}
