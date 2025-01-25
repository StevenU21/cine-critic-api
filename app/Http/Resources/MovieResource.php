<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'release_date' => $this->release_date,
            'genre_id' => GenreResource::make($this->whenLoaded('genre')),
            'director_id' => DirectorResource::make($this->whenLoaded('director')),
            'average_rating' => $this->averageRating(),
            'created_at' => $this->when($user && $user->hasRole('admin') && $request->has('include_timestamps'), $this->created_at->format('Y-m-d H:i:s')),
            'updated_at' => $this->when($user && $user->hasRole('admin') && $request->has('include_timestamps'), $this->updated_at->format('Y-m-d H:i:s')),
        ];
    }
}
