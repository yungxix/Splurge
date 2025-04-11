<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SplurgeEventUserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'title' => $this->title,
            'tag' => $this->tag,
            'role' => $this->role,
            'barcode_image_url' => $this->barcode_image_url,
            'splurge_event' => new SplurgeEventResource($this->whenLoaded('splurgeEvent')),
            'menu_items' => GuestMenuItemResource::collection($this->whenLoaded('menuItems')),
            'tables' => AssignedVenueTableResource::collection($this->whenLoaded('tables')),
            'bag_items' => GuestUserBagItemResource::collection($this->whenLoaded('bagItems')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
