<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DirectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($request->has('include_id'), $this->id),
            'name' => $this->title,
            'biography' => $this->biography,
            'image' => $this->image(),
            'birth_date' => $this->birth_date,
            'nationality' => $this->nationality,
            'created_at' => $this->when($request->has('include_timestamps'), $this->created_at),
            'updated_at' => $this->when($request->has('include_timestamps'), $this->updated_at),
        ];
    }
}
