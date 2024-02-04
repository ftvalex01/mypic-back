<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostHashtagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'hashtag_id' => $this->hashtag_id,
            'hashtag' => HashtagResource::make($this->whenLoaded('hashtag')),
        ];
    }
}
