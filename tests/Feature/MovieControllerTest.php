<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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

    public function test_admin_can_create_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(201);
    }

    public function test_moderator_cant_create_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(403);
    }

    public function test_reviewer_cant_create_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(403);
    }

    public function test_guest_user_cant_create_movie()
    {
        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->post('/api/movies', $movieData);

        $response->assertStatus(401);
    }

    public function test_admin_can_update_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(200);
    }

    public function test_moderator_can_update_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(200);
    }

    public function test_reviewer_cant_update_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(403);
    }

    public function test_guest_user_cant_update_movie()
    {
        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(401);
    }

    public function test_admin_can_delete_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/movies/$movie->id");

        $response->assertStatus(204);
    }

    public function test_moderator_cant_delete_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/movies/$movie->id");

        $response->assertStatus(403);
    }

    public function test_reviewer_cant_delete_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/movies/$movie->id");

        $response->assertStatus(403);
    }

    public function test_guest_user_cant_delete_movie()
    {
        $movie = Movie::factory()->create();

        $response = $this->delete("/api/movies/$movie->id");

        $response->assertStatus(401);
    }
}
