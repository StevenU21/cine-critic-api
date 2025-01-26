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
            'image_url' => $this->image,
            'release_date' => $this->release_date,
            'trailer_url' => $this->trailer_url,
            'duration' => $this->duration,
            'genre' => $this->genre->name,
            'director' => $this->director->name,
            'average_rating' => $this->ratingAverage ? $this->ratingAverage->aggregate : 0,
            'reviews_count' => $this->reviewsCount ? $this->reviewsCount->aggregate : 0,
            'created_at' => $this->when($user && $user->hasRole('admin') && $request->has('include_timestamps'), $this->created_at->format('Y-m-d H:i:s')),
            'updated_at' => $this->when($user && $user->hasRole('admin') && $request->has('include_timestamps'), $this->updated_at->format('Y-m-d H:i:s')),
        ];
    }
}
