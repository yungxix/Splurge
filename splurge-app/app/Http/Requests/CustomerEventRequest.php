<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\DB;

use App\Models\CustomerEvent;

use App\Models\Booking;

use App\Models\Customer;

use App\Models\Address;
use Illuminate\Support\Arr;


class CustomerEventRequest extends FormRequest
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
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'customer_event' => 'array',
                'booking' => 'sometimes|array',
                'customer_event.name' => 'required|max:255',
                'customer_event.event_date' => 'required|date',
                'booking.description' => 'nullable|max:400',
                'booking.location' => 'sometimes|array',
                'booking.location.line1' => 'sometimes|max:200',
                'booking.location.line2' => 'sometimes|max:200',
                'booking.location.state' => 'sometimes',
                'booking.customer' => 'sometimes|array',
                'booking.service_tier_id' => 'sometimes',
                'booking.customer.full_name' => 'sometimes|max:230',
                'booking.customer.email' => 'nullable|email',
                'booking.customer.phone' => 'nullable|max:' . (12 * 3)
            ];    
        }
        return [
            'customer_event' => 'array',
            'booking' => 'array',
            'customer_event.name' => 'required|max:255',
            'customer_event.event_date' => 'required|date',
            'booking.description' => 'nullable|max:400',
            'booking.location' => 'array',
            'booking.location.line1' => 'required|max:200',
            'booking.location.line2' => 'nullable|max:200',
            'booking.location.state' => 'required',
            'booking.customer' => 'required',
            'booking.service_tier_id' => 'required',
            'booking.customer.full_name' => 'required|max:230',
            'booking.customer.email' => 'nullable|email',
            'booking.customer.phone' => 'nullable|max:' . (12 * 3)
        ];
    }

    public function commitNew() {
        return DB::transaction(function () {
            return $this->commitNewImpl();
        });
    }

    public function commitEdit(CustomerEvent $event) {
        return DB::transaction(function () use ($event) {
            return $this->commitEditImpl($event);
        });
    }

    private function commitEditImpl(CustomerEvent $event) {
        

        $event_data = $this->input('customer_event');

        

        $event->update($event_data);

        $booking = $this->input('booking', []);


        
        $event->booking->update(array_merge(
            [],
            Arr::only($booking, 'description'),
            Arr::only($event_data, 'event_date')
        ));
        
        $customer_data = $this->input('booking.customer');

        if (!is_null($customer_data)) {
            $event->booking->customer()->update(Arr::only($customer_data, ['first_name', 'last_name', 'email', 'phone']));
        }
        
        $address = $this->input('booking.location');
        if (!is_null($address)) {
            $event->booking->location()->update($address);
        }

        return $event;
    }

    private function commitNewImpl() {
        $event = $this->grabNewCustomerEvent();
        $booking = $this->grabBooking();
        $customer = $this->grabCustomer();
        $customer->saveOrFail();
        $customer->bookings()->save($booking);
        $booking->location()->save($this->grabAddress());
        $event->booking_id = $booking->id;
        $event->saveOrFail();
        return $event;
    }

    private function grabBooking() {
        $data = $this->input('booking');
        $booking = new Booking([
            'description' => Arr::get($data, 'description'),
            'event_date' => $this->input('customer_event.event_date'),
            'service_tier_id' => Arr::get($data, 'service_tier_id')
        ]);
        $booking->code = $booking->generateCode();


        return $booking;

    }

    private function grabNewCustomerEvent() {
        $event = new CustomerEvent($this->input('customer_event'));
        return $event;
    }

    private function grabCustomer() {
        $customer_data = $this->input('booking.customer');
        $full_name = explode(' ', $customer_data['full_name']);
        $customer = new Customer([
            'first_name' => $full_name[0],
            'last_name' => implode(' ', array_slice($full_name, 1)),
            'email' => Arr::get($customer_data, 'email'),
            'phone' => Arr::get($customer_data, 'phone')
        ]);
        return $customer;
    }

    private function grabAddress() {
        $location = $this->input('booking.location');
        return new Address([
            'line1' => Arr::get($location, 'line1'),
            'line2' => Arr::get($location, 'line2'),
            'state' => Arr::get($location, 'state'),
            'country' => 'NG'
        ]);

    }
}
