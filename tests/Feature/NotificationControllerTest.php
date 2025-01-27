<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use App\Notifications\CreatedMovieNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    protected function createMovie($user)
    {
        $movieData = Movie::factory()->make()->toArray();
        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        return $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->post('/api/movies', $movieData);
    }

    public function test_admin_user_can_view_notification_list()
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->createMovie($user);

        $response->assertStatus(201);

        Notification::assertSentTo($user, CreatedMovieNotification::class);

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->get('/api/notifications');

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_notification_list()
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->assignRole('moderator');

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->get('/api/notifications');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_notification_list()
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->get('/api/notifications');

        $response->assertStatus(200);
    }

    public function test_guest_user_cannot_view_notification_list()
    {
        $response = $this->get('/api/notifications');

        $response->assertStatus(401);
    }
}
