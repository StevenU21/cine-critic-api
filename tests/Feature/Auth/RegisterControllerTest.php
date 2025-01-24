<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function test_user_can_register()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_registered_user_has_reviewer_role()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@gmail.com')->first();

        $this->assertTrue($user->hasRole('reviewer'));

        $response->assertStatus(200);
    }

    public function test_registration_requires_name_email_and_password()
    {
        $response = $this->postJson(route('register'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_registration_requires_valid_email()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_registration_requires_password_confirmation()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_registration_requires_unique_email()
    {
        $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response = $this->postJson(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
