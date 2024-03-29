<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerEventResource extends JsonResource
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
            'event_date' => $this->resource->event_date,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'require_guest_confirmation' => $this->resource->require_guest_confirmation,
            $this->mergeWhen($this->resource->relationLoaded('booking'), [
                'booking' => new BookingResource($this->resource->booking)
            ]),
            $this->mergeWhen($request->has('include_guest_count'), [
                'guests_count' => $this->resource->guests_count
            ]),
            'guests' => CustomerEventGuestResource::collection($this->whenLoaded('guests'))
        ];
    }
}
