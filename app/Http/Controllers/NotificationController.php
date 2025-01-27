<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotificationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        return NotificationResource::collection($notifications);
    }

    public function markAsRead(string $id): void
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
    }

    public function destroy(string $id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
        }

        return response()->json(null, 204);
    }

    public function destroyAll()
    {
        $user = auth()->user();
        $user->notifications()->delete();

        return response()->json(null, 204);
    }
}
