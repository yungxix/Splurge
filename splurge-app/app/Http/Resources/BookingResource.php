<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    private $verificationCode;
    public function __construct(Booking $model, $verificationCode = NULL)
    {
        parent::__construct($model);
        $this->verificationCode = $verificationCode;
    }
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
            'code' => $this->resource->code,
            'description' => $this->resource->description,
            'event_date' => $this->resource->event_date,
            'location' => $this->when($this->resource->relationLoaded('location'), fn () => new AddressResource($this->resource->location)),
            'customer' => $this->when($this->resource->relationLoaded('customer'), fn () => new CustomerResource($this->resource->customer)),
            'verificationCode' => $this->when(!is_null($this->verificationCode), $this->verificationCode),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'service_tier_id' => $this->resource->service_tier_id,
            'payments' => PaymentResource::collection($this->whenLoaded("payments")),
            'current_charge' => $this->resource->current_charge,
            'service_tier' => $this->when($this->resource->relationLoaded('serviceTier'), fn () => new ServiceTierResource($this->resource->serviceTier)),
        ];
    }
}
