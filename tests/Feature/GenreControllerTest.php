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

    public function test_user_can_view_genre_list()
    {
        Genre::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/genres');

        $response->assertStatus(200);
    }

    public function test_user_can_show_specify_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/genres/$genre->id");

        $response->assertStatus(200);
    }

    public function test_user_without_view_permission_cannot_view_genre_list()
    {
        Genre::factory(10)->create();

        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/genres');

        $response->assertStatus(403);
    }

    public function test_user_can_create_genre()
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

    public function test_user_without_create_permission_cannot_create_genre()
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/genres', [
            'name' => 'Fantasy',
            'description' => 'The best genre ever',
        ]);

        $response->assertStatus(403);
    }

    
}
