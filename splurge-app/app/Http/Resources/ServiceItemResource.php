<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceItemResource extends JsonResource
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
            'price' => $this->resource->price,
            'pricing_type' => $this->resource->pricing_type,
            'type' => $this->resource->pricing_type,
            'options' => $this->resource->options ?: ['x' => '0'],
            'category' => $this->resource->category,
            'required' => $this->resource->required ? true : false,
            'image_url' => $this->when(!empty($this->resource->image_url), splurge_asset($this->resouce->image_url))
        ];
    }
}
