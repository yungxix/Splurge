<?php

namespace App\Http\Requests\Admin;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;

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
            'event_date' => 'date',
            'description' => "nullable|max:255",
            "price" => "nullable|numeric"
        ];
    }

    public function updateBooking(Booking $booking) {
        $attrs = $this->safe();
        $update_attrs = $attrs->only(['event_date', 'description']);
        if (!empty($attrs->price)) {
            $update_attrs['current_charge'] = $attrs->price;
        }
        $booking->update($update_attrs);    
    }
}
