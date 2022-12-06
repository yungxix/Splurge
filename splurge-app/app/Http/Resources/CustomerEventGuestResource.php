<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerEventGuestResource extends JsonResource
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
            'gender' => $this->resource->gender,
            'accepted' => $this->resource->accepted,
            'presented' => $this->resource->presented,
            'attended_at' => $this->resource->getAttendanceTime(),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'table' => $this->resource->table_name,
            'barcode_image_url' => $this->resource->barcode_image_url,
            'tag' => $this->resource->tag
        ];
    }
}
