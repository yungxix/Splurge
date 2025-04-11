<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SplurgeEvent;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Arr;


class CreateSplurgeEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user('api')->can('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
            'event_date' => ['required', 'date'],
            'service_tier_id' => ['required', 'integer'],
            'description' => ['required', 'max:500'],
            'customer.first_name' => ['required', 'max:120'],
            'customer.last_name' => ['sometimes', 'nullable', 'max:120'],
            'customer.email' => ['required', 'email'],
            'customer.phone' => ['required', 'max:15'],
            'customer.gender' => ['sometimes', 'nullable', 'max:15'],
            'customer.title' => ['sometimes', 'nullable', 'max:30'],
            'location.line1' => ['required', 'max:255'],
            'location.line2' => ['sometimes', 'nullable', 'max:255'],
            'location.state' => ['required', 'max:255'],
            'location.name' => ['sometimes', 'nullable', 'max:150'],
        ];
    }


    public function commit(): SplurgeEvent {
       return DB::transaction(function () {
            $event = new SplurgeEvent($this->safe()->only(['name', 'event_date', 'service_tier_id', 'description']));
            $event->code = $event->generateCode();

            $event->saveOrFail();


            $event->members()->create(array_merge(
                $this->safe()->input['customer'],
                [
                    'tag' => "splurge://events/{$event->code}/customer",
                    "role" => "CUSTOMER"
                ]
                ));


            $event->locations()->create(array_merge(
                $this->safe()->input['location'],
                [
                    'purpose' => 'BOOKING'
                ]
                ));

            return $event;
        });
    }
}
