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

        $this->assertTrue($notification->fresh()->read());
    }

    public function test_moderator_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

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

        $this->assertTrue($notification->fresh()->read());
    }

    public function test_reviewer_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

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

        $this->assertTrue($notification->fresh()->read());
    }

    public function test_guest_user_cannot_mark_notification_as_read()
    {
        $user = User::factory()->create();

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

        $this->assertFalse($notification->fresh()->read());
    }

    public function test_admin_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->put('/api/notifications/mark-all-as-read');

        $response->assertStatus(200);

        $this->assertTrue($user->notifications->first()->fresh()->read());
    }

    public function test_moderator_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->put('/api/notifications/mark-all-as-read');

        $response->assertStatus(200);

        $this->assertTrue($user->notifications->first()->fresh()->read());
    }

    public function test_reviewer_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->put('/api/notifications/mark-all-as-read');

        $response->assertStatus(200);

        $this->assertTrue($user->notifications->first()->fresh()->read());
    }

    public function test_guest_user_cannot_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->put('/api/notifications/mark-all-as-read');

        $response->assertStatus(401);

        $this->assertFalse($user->notifications->first()->fresh()->read());
    }

    public function test_admin_user_can_delete_notification()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->delete("/api/notifications/{$notification->id}");

        $response->assertStatus(204);

        $this->assertNull($notification->fresh());
    }

    public function test_moderator_user_can_delete_notification()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->delete("/api/notifications/{$notification->id}");

        $response->assertStatus(204);

        $this->assertNull($notification->fresh());
    }

    public function test_reviewer_user_can_delete_notification()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->delete("/api/notifications/{$notification->id}");

        $response->assertStatus(204);

        $this->assertNull($notification->fresh());
    }

    public function test_guest_user_cannot_delete_notification()
    {
        $user = User::factory()->create();

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $notification = $user->notifications()->first();

        $response = $this->delete("/api/notifications/{$notification->id}");

        $response->assertStatus(401);

        $this->assertNotNull($notification->fresh());
    }

    public function test_admin_user_can_delete_all_notifications()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->delete('/api/notifications');

        $response->assertStatus(204);

        $this->assertCount(0, $user->notifications);
    }

    public function test_moderator_user_can_delete_all_notifications()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->delete('/api/notifications');

        $response->assertStatus(204);

        $this->assertCount(0, $user->notifications);
    }

    public function test_reviewer_user_can_delete_all_notifications()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer {$user->createToken('auth_token')->plainTextToken}")
            ->delete('/api/notifications');

        $response->assertStatus(204);

        $this->assertCount(0, $user->notifications);
    }

    public function test_guest_user_cannot_delete_all_notifications()
    {
        $user = User::factory()->create();

        if (!$user->notifications()->exists()) {
            $user->notifications()->create([
                'id' => '1c8c82c1-cfd6-4290-a939-f15a2034a958',
                'type' => 'App\Notifications\SomeNotification',
                'data' => ['message' => 'Test notification'],
            ]);
        }

        $response = $this->delete('/api/notifications');

        $response->assertStatus(401);

        $this->assertCount(1, $user->notifications);
    }
}
