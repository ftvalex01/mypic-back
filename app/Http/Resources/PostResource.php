<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'publish_date' => $this->publish_date,
            'life_time' => $this->life_time,
            'permanent' => $this->permanent,
            'media_id' => $this->media_id,
            'media' => MediaResource::make($this->whenLoaded('media')),
            'interactionHistories' => InteractionHistoryCollection::make($this->whenLoaded('interactionHistories')),
        ];
    }
}
