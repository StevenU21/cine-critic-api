<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolesAndPermissionsSeeder;
use Tests\TestCase;
use App\Models\Genre;

class GenreControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_user_can_view_genre_list()
    {
        Genre::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/genres');

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_genre_list()
    {
        Genre::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/genres');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_genre_list()
    {
        Genre::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/genres');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_show_specify_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/genres/$genre->id");

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_show_specify_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/genres/$genre->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_show_specify_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/genres/$genre->id");

        $response->assertStatus(200);
    }

    public function test_admin_user_can_create_genre()
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/genres', [
            'name' => 'Fantasy',
            'description' => 'The best genre ever',
        ]);

        $response->assertStatus(201);
    }

    public function test_moderator_user_cant_create_genre()
    {
        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/genres', [
            'name' => 'Fantasy',
            'description' => 'The best genre ever',
        ]);

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_create_genre()
    {
        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/genres', [
            'name' => 'Fantasy',
            'description' => 'The best genre ever',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_user_can_update_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/genres/$genre->id", [
            'name' => 'Fantasy',
            'description' => 'The best genre ever',
        ]);

        $response->assertStatus(200);
    }

        public function test_moderator_user_cant_update_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/genres/$genre->id", [
            'name' => 'Fantasy',
            'description' => 'The best genre ever',
        ]);

        $response->assertStatus(403);
    }

    public function test_reviewer_user_cant_update_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/genres/$genre->id", [
            'name' => 'Fantasy',
            'description' => 'The best genre ever',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_user_can_delete_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/genres/$genre->id");

        $response->assertStatus(200);
    }

    public function test_user_without_delete_permission_cannot_delete_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/genres/$genre->id");

        $response->assertStatus(403);
    }


}
