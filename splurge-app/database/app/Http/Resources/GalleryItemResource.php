<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'content' => $this->resource->content,
            'heading' => $this->resource->heading,
            'created_at' => $this->resource->created_at,
            'media_items' => MediaOwnerResource::collection($this->whenLoaded('mediaItems'))
        ];
    }
}
