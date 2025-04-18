<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignedVenueTableResource extends JsonResource
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
            'event_user_id' => $this->event_user_id,
            'table_id' => $this->table_id,
            'event_user' => new SplurgeEventUserResource($this->whenLoaded('guest')),
            'venue_table' => new VenueTableResource($this->whenLoaded('venueTable')),
        ];
    }
}
