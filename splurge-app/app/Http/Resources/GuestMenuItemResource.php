<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestMenuItemResource extends JsonResource
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
            'guest_id' => $this->event_user_id,
            'event_user_id' => $this->event_user_id,
            'menu_item_id' => $this->menu_item_id,
            'menu_item' => new MenuItemResource($this->whenLoaded('menuItem'))
        ];
    }
}
