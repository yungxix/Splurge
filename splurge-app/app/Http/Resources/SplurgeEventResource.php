<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SplurgeEventResource extends JsonResource
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
          'code' => $this->code,
          'description' => $this->description,
          'event_date' => $this->event_date,
          'status' => $this->status,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
          'venues' => AddressResource::collection($this->whenLoaded('location')),
          'service_tier' => new ServiceTierResource($this->whenLoaded('serviceTier')),
          'members' => SplurgeEventUserResource::collection($this->whenLoaded('members')),
        ];
    }
}
