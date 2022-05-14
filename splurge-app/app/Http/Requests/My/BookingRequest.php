<?php

namespace App\Http\Requests\My;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'description' => 'max:255',
        'customer' => 'nullable|array',
        'customer.email' =>  'email',
        'customer.first_name' => 'max:120',
        'customer.last_name' => 'max:120',
        'customer.phone' => 'max:14',
        ];
    }

    public function updateBooking(Booking $booking) {
        return DB::transaction(function () use ($booking) {
            $attributes = $this->safe();

            $primaryAttributes = $attributes->only('description');
            if (!empty($primaryAttributes)) {
                $booking->update($primaryAttributes);
            }
            if (!is_null($attributes->customer)) {
                $booking->customer->update($attributes->customer);
            }

            return $booking;
        });
        
    }
}
