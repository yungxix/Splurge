<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'image_url' => empty($this->resource->image_url) ? null : splurge_asset($this->resource->image_url),
            'thumbnail_image_url' => empty($this->resource->thumbnail_image_url) ? null : splurge_asset($this->resource->thumbnail_image_url)
        ];
    }
}
