<?php

namespace Tests\Feature;

use App\Notifications\CreatedMovieNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;

class BroadcastConnectionTest extends TestCase
{
    public function test_notification_is_broadcasted()
    {
        Notification::fake();

        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $user->notify(new CreatedMovieNotification($movie, $user));

        // Verify a notification was sent to the given users...
        Notification::assertSentTo(
            [$user],
            CreatedMovieNotification::class,
            function ($notification, $channels) use ($user, $movie) {
                // Verify the notification was sent on the correct channels
                $this->assertContains('broadcast', $channels);

                // Verify the broadcast channel is correct
                $this->assertEquals(
                    'notifications.' . $user->id,
                    $notification->broadcastOn()->name
                );

                // Verify the broadcast data is correct
                $this->assertEquals([
                    'type' => 'created',
                    'user_name' => $user->name,
                    'message' => 'The movie ' . $movie->title . ' has been created',
                    'movie_id' => $movie->id,
                ], $notification->toArray($user));

                return true;
            }
        );
    }

    public function test_notification_is_broadcasted_to_all_users()
    {
        Notification::fake();

        $movie = Movie::factory()->create();

        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $user->notify(new CreatedMovieNotification($movie, $user));
        }

        // Verify a notification was sent to the given users...
        Notification::assertSentTo(
            $users,
            CreatedMovieNotification::class,
            function ($notification, $channels, $notifiable) use ($users, $movie) {
                // Verify the notification was sent on the correct channels
                $this->assertContains('broadcast', $channels);

                // Verify the broadcast channel is correct
                $this->assertEquals(
                    'notifications.' . $notifiable->id,
                    $notification->broadcastOn()->name
                );

                // Verify the broadcast data is correct
                $this->assertEquals([
                    'type' => 'created',
                    'user_name' => $notifiable->name,
                    'message' => 'The movie ' . $movie->title . ' has been created',
                    'movie_id' => $movie->id,
                ], $notification->toArray($notifiable));

                return true;
            }
        );
    }

    public function test_notification_is_not_broadcasted_to_unauthenticated_users()
    {
        Notification::fake();

        $movie = Movie::factory()->create();

        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $user->notify(new CreatedMovieNotification($movie, $user));
        }

        // Verify a notification was sent to the given users...
        Notification::assertSentTo(
            $users,
            CreatedMovieNotification::class,
            function ($notification, $channels, $notifiable) use ($users, $movie) {
                // Verify the notification was sent on the correct channels
                $this->assertContains('broadcast', $channels);

                // Verify the broadcast channel is correct
                $this->assertEquals(
                    'notifications.' . $notifiable->id,
                    $notification->broadcastOn()->name
                );

                // Verify the broadcast data is correct
                $this->assertEquals([
                    'type' => 'created',
                    'user_name' => $notifiable->name,
                    'message' => 'The movie ' . $movie->title . ' has been created',
                    'movie_id' => $movie->id,
                ], $notification->toArray($notifiable));

                return true;
            }
        );
    }
}
