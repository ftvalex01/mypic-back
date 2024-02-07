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
            'description' => $this->description,
            'publish_date' => $this->publish_date,
            'life_time' => $this->life_time,
            'permanent' => $this->permanent,
            'isLiked' => $this->isLikedByUser(auth()->id()),
            'likesCount' => $this->reactions->count(),

            'user' => new UserResource($this->whenLoaded('user')),
            'media' => MediumResource::make($this->whenLoaded('media')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'reactions' => ReactionResource::collection($this->whenLoaded('reactions')),
            //'interactionHistories' => InteractionHistoryCollection::make($this->whenLoaded('interactionHistories')),
        ];
    }
}
