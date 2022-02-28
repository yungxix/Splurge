<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaOwnerResource extends JsonResource
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
            'url' => ResourceUtils::safeAssetUrl($this->resource->url),
            'thumbnail_url' => ResourceUtils::safeAssetUrl($this->resource->thumbnail_url),
            'media_type' => $this->resource->media_type,
            'created_at' => $this->resource->created_at,
            'owner_type' => $this->resource->owner_type,
            'owner_id' => $this->resource->owner_id,
            'image_options' => $this->resource->image_options ?: ['_x' => '0'],
            'name' => $this->resource->name
        ];
    }
}
