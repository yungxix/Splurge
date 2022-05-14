<?php

namespace App\Http\Requests;

use App\Events\NewBookingCreatedEvent;
use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Models\Booking;

use App\Models\Address;

use Illuminate\Support\Facades\DB;

use App\Models\ServiceTier;

use App\Models\Customer;
use App\Models\Payment;

class NewBookingRequest extends FormRequest
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
        $minDate = Carbon::now()->addDay(1)->format('Y-m-d');
        return [
            'description' => 'required|max:255',
            'event_date' => "required|date|min:$minDate",
            "customer" => "required|array",
            "customer.first_name" => 'required|max:120',
            "customer.last_name" => 'required|max:120',
            "customer.email" => 'required|email',
            "customer.phone" => 'required|max:15',
            "service_tier_id" => "required",
            "address" => "required|array",
            "address.name" => "max:255",
            "address.line1" => "required|max:255",
            "address.line2" => "max:255",
            "address.state" => "required|max:25"
        ];
    }

    public function hasPayment() {
        return $this->input("payment.amount", 0) > 0;
    }

    public function acceptBooking() {
        

        return DB::transaction(function () {


            $sanitized = $this->safe();

            $tier = ServiceTier::findOrFail($sanitized->service_tier_id);

            $booking = new Booking($sanitized->only(['description', 'service_tier_id']));

            // I did it this way because I want to use carbon capability for calls
            $booking->event_date = Carbon::parse($sanitized->event_date);

            if ($tier->price > 0) {
                $booking->current_charge = $tier->price;
            } 
            
            
            $booking->code = $booking->generateCode();

            $customer = Customer::create($sanitized->customer);

            $address = new Address($sanitized->address);

            $customer->bookings()->save($booking);

            $booking->location()->save($address);

            event(new NewBookingCreatedEvent($booking));

            return $booking;

        });
    }
}
