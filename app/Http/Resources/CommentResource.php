<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'text' => $this->text,
            'comment_date' => $this->comment_date,
            'user' => UserResource::make($this->whenLoaded('user')),

            'reactions' => ReactionCollection::make($this->whenLoaded('reactions')),
        ];
    }
}
