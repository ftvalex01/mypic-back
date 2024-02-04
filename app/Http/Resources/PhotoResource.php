<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'album_id' => $this->album_id,
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
            'upload_date' => $this->upload_date,
            'album' => AlbumResource::make($this->whenLoaded('album')),
        ];
    }
}
