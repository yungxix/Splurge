<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VenueTableResource extends JsonResource
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
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'location_id' => $this->address_id,
            'venue_id' => $this->address_id,
            'address_id' => $this->address_id,
            'venue' => $this->when($this->resource->relationLoaded('location'), fn () => new AddressResource($this->resource->location)),
            'assigned_guests' => AssignedVenueTableResource::collection($this->whenLoaded('assignments')),
        ];
    }
}
