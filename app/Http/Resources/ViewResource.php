<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'story_id' => $this->story_id,
            'view_date' => $this->view_date,
            'story' => StoryResource::make($this->whenLoaded('story')),
        ];
    }
}
