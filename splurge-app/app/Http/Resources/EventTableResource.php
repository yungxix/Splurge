<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventTableResource extends JsonResource
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
            'created_at' => $this->created_at,
            'name' => $this->name,
            'capactity' => $this->capacity,
            'available_capactity' => $this->when($this->resource->isAvailabilitySet(), $this->resource->getAvailable())
        ];
    }
}
