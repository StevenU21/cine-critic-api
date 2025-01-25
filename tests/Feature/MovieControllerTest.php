<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    public function test_admin_user_can_view_movie_list()
    {
        Movie::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies');

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_movie_list()
    {
        Movie::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_movie_list()
    {
        Movie::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_guest_user_can_not_view_movie_list()
    {
        $response = $this->get('/api/movies');

        $response->assertStatus(401);
    }

    public function test_guest_user_can_not_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $response = $this->get("/api/movies/$movie->id");

        $response->assertStatus(401);
    }
}
