<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTierResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'description' => $this->resource->description,
            'options' => $this->resource->options ?: [],
            'footer_message' => $this->resource->footer_message,
            'code' => $this->resource->code,
            'service_id' => $this->resource->service_id,
            'service' => $this->when($this->resource->relationLoaded('service'), fn () => new ServiceResource($this->resource->service)),
            'image_url' => empty($this->resource->image_url) ? null : splurge_asset($this->resource->image_url)
        ];
    }
}
