<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    // use RefreshDatabase;

    // public function test_user_can_login()
    // {
    //     $user = User::factory()->create([
    //         'email' => 'john@gmail.com',
    //         'password' => Hash::make('password'),
    //     ]);

    //     $response = $this->postJson(route('login'), [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);

    //     $response->assertStatus(200);
    // }

    // public function test_login_requires_email_and_password()
    // {
    //     $response = $this->postJson(route('login'), []);

    //     $response->assertStatus(422);
    //     $response->assertJsonValidationErrors(['email', 'password']);
    // }

    // public function test_user_can_logout()
    // {
    //     $user = User::factory()->create();

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     $response = $this->withHeader('Authorization', "Bearer $token")
    //         ->postJson(route('logout'));

    //     $response->assertStatus(200);
    // }

    // public function test_user_can_logout_only_when_authenticated()
    // {
    //     $response = $this->postJson(route('logout'));

    //     $response->assertStatus(401);
    // }

}
