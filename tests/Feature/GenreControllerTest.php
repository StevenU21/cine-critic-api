<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Genre;

class GenreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_genre_list()
    {
        Genre::factory(10)->create();

        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/genres');

        $response->assertStatus(200);
    }

    public function test_user_can_show_specify_genre()
    {
        $genre = Genre::factory()->create();

        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/genres/$genre->id");

        $response->assertStatus(200);
    }
}
