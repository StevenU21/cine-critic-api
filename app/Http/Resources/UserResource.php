<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->when($user && $user->hasRole('admin') && $request->has('include_id'), $this->id),
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->when($user && ($user->hasRole('admin') || $user->hasRole('moderator')), function () {
                return $this->roles->first()->name;
            }),
            'permissions' => $this->when($user && $user->hasRole('admin'), function () {
                return $this->roles->flatMap(function ($role) {
                    return $role->permissions->pluck('name');
                })->unique()->values();
            }),
            'created_at' => $this->when($user && $user->hasRole('admin') && $request->has('include_timestamps'), $this->created_at->format('Y-m-d H:i:s')),
        ];
    }
}
