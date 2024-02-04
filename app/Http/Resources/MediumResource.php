<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'url' => $this->url,
            'upload_date' => $this->upload_date,
            'user' => UserResource::make($this->whenLoaded('user')),
            'posts' => PostCollection::make($this->whenLoaded('posts')),
        ];
    }
}
