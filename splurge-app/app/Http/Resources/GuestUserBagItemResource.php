<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestUserBagItemResource extends JsonResource
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
            'item_count' => $this->item_count,
            'item_type' => $this->item_type,
            'name' => $this->name,
            'confirmed_at' => $this->confirmed_at,
            'confirmed_by' => $this->confirmed_by,
            'event_user_id' => $this->event_user_id,
            'event_user' => $this->when($this->resource->relationLoaded('eventUser'), fn () => new SplurgeEventUserResource($this->resource->event_user)),
        ];
    }
}
