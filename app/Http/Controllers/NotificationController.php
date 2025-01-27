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

    public function show(Request $request): NotificationResource
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $request->id)->first();

        return new NotificationResource($notification);
    }

    public function markAsRead(Request $request): void
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $request->id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
    }

    public function destroy(Request $request): void
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $request->id)->first();
        if ($notification) {
            $notification->delete();
        }
    }

    public function destroyAll(): void
    {
        $user = auth()->user();
        $user->notifications()->delete();
    }
}
