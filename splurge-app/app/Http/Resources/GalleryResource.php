<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
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
            'id' => $this->id,
            'caption' => $this->caption,
            'image_url' => ResourceUtils::safeAssetUrl($this->image_url),
            'description' => $this->description,
            'items' => GalleryItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'image_options' => $this->resource->image_options ?: ['_x' => '0']
        ];
    }
}
