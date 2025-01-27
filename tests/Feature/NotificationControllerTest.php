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
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->get('/api/notifications');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_notification_list()
    {
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

    public function test_admin_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Crear una notificación para el usuario si no existe
        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->put("/api/notifications/{$notification->id}/mark-as-read");

        $response->assertStatus(200);

        // Asegurarse de que la notificación esté marcada como leída
        $this->assertTrue($notification->fresh()->read());
    }

    public function test_moderator_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        // Crear una notificación para el usuario si no existe
        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->put("/api/notifications/{$notification->id}/mark-as-read");

        $response->assertStatus(200);

        // Asegurarse de que la notificación esté marcada como leída
        $this->assertTrue($notification->fresh()->read());
    }

    public function test_reviewer_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        // Crear una notificación para el usuario si no existe
        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->put("/api/notifications/{$notification->id}/mark-as-read");

        $response->assertStatus(200);

        // Asegurarse de que la notificación esté marcada como leída
        $this->assertTrue($notification->fresh()->read());
    }

    public function test_guest_user_cannot_mark_notification_as_read()
    {
        $user = User::factory()->create();

        // Crear una notificación para el usuario si no existe
        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->put("/api/notifications/{$notification->id}/mark-as-read");

        $response->assertStatus(401);

        // Asegurarse de que la notificación no esté marcada como leída
        $this->assertFalse($notification->fresh()->read());
    }
}
