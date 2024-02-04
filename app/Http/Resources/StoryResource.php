<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'publish_date' => $this->publish_date,
            'expiration_date' => $this->expiration_date,
            'user' => UserResource::make($this->whenLoaded('user')),
            'reactions' => ReactionCollection::make($this->whenLoaded('reactions')),
        ];
    }
}
