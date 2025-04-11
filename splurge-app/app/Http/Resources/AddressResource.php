<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'line1' => $this->resource->line1,
            'line2' => $this->resource->line2,
            'zip' => $this->resource->zip,
            'name' => $this->resource->name,
            'state' => $this->resource->state,
            'latitude' => $this->resource->latitude,
            'longitude' => $this->resource->longitude,
            'purpose' => $this->resource->purpose,
            'tables' => VenueTableResource::collection($this->whenLoaded('tables'))
            
        ];
    }
}
