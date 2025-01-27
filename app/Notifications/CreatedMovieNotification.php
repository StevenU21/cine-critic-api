<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Broadcasting\Channel;

class CreatedMovieNotification extends Notification
{
    use Queueable;

    protected $movie;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(Movie $movie, User $user)
    {
        $this->movie = $movie;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'created',
            'user_name' => $this->user->name,
            'message' => 'The movie ' . $this->movie->title . ' has been created',
            'movie_id' => $this->movie->id,
        ];
    }

    public function broadcastOn(): Channel
    {
        return new Channel('notifications.' . $this->user->id);
    }

    public function broadcastType(): string
    {
        return 'created';
    }
}
