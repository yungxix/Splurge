@php
    use Illuminate\Support\Arr;
@endphp

<form  class="mt-4 md:w-4/5" action="{{ $url }}" method="POST">
    @isset($method)
        @method($method)
    @endisset
    @csrf
    <x-forms.form-group :field="'customer_event[name]'" :errors="$errors">
        <x-slot:label>
            Name or caption of the event
        </x-slot:label>
        <input type="text" name="customer_event[name]"
         class="control w-full md:w-4/5" 
         required placeholder="Name of event" value="{{ Arr::get($customer_event, 'name') }}" />
    </x-forms.form-group>
    <x-forms.form-group :field="'customer_event[event_date]'" :errors="$errors">
        <x-slot:label>
            Date of event
        </x-slot:label>
        <input type="date" name="customer_event[event_date]"
             required  
             @class(['control', 'w-full',  'md:w-4/5', 'error' => $errors->has('customer_event.event_date')]) 
             placeholder="Date of event" value="{{ splurge_date_to_string(Arr::get($customer_event, 'event_date'), 'Y-m-d') }}" />
    </x-forms.form-group>


    <x-forms.form-group :field="'booking[description]'" :errors="$errors">
        <x-slot:label>
            [Optional] describe the event
        </x-slot:label>
        <textarea type="date" name="booking[description]"
         @class(['control', 'w-full',  'md:w-4/5', 'error' => $errors->has('booking.description')]) 
         rows="4" placeholder="Description of event">{{ Arr::get($customer_event, 'booking.description') }}</textarea>
         
    </x-forms.form-group>

    

    <fieldset class="mb-8">
        <legend>
            Service
        </legend>
        <p class="m-4 p-4 round-md bg-stone-300">
            <em>
                A service is always required for recording a booking for this event. Just choose a service even if it is not revant in reallife
            </em>
        </p>
        <select name="booking[service_tier_id]" @class(['control', 'w-full', 'error' => $errors->has("booking.service_tier_id")])>
            @foreach ($services as $service)
                @if ($service->tiers->isEmpty())
                    @continue
                @endif

                <optgroup label="{{ $service->name }}">
                    @foreach ($service->tiers as $tier)
                        <option @selected($tier->id == Arr::get($customer_event, 'booking.service_tier_id')) value="{{ $tier->id }}">{{ $tier->name }}</option>
                    @endforeach
                </optgroup>

            @endforeach
        </select>
        @error("booking.service_tier_id")
            <p class="text-red-600">{{ $message }}</p>
        @enderror
    </fieldset>

    <hr class="mx-auto w-4/5 my-4" />

    <p class="p-4 text-center text-gray-800">
        <em>An event must be associated with a customer/client name and location for documentation purpose</em>
    </p>

    <fieldset>
        <legend>Customer</legend>
        @foreach (['full_name' => 'Full name', 'email' => 'Email address', 'phone' => 'Phone Number'] as $field => $label )
        <x-forms.form-group :field="'booking[customer][' . $field .']'" :errors="$errors">
            <x-slot:label>
                {{ $label}}
            </x-slot:label>
            @switch($field)
                @case('email')
                    <input type="email" name="booking[customer][{{ $field }}]" @class([
                        'control', 'w-full', 'md:w-4/5',
                        'error' => $errors->has("booking.customer.$field")
                    ]) required placeholder="Enter email address" 
                        value="{{ Arr::get($customer_event, 'booking.customer.' . $field) }}"
                    />
                    @break
                @case('phone')
                <input type="tel"
                @class([
                    'control', 'w-full', 'md:w-4/5',
                    'error' => $errors->has("booking.customer.$field")
                ])
                name="booking[customer][{{ $field }}]" placeholder="Enter phone number"
                        value="{{ Arr::get($customer_event, 'booking.customer.' . $field) }}"
                    />
            
                @break
            
                @default
                <input type="text" @class([
                    'control', 'w-full', 'md:w-4/5',
                    'error' => $errors->has("booking.customer.$field")
                ]) required name="booking[customer][{{ $field }}]" placeholder="Enter {{ $label }}"
                    value="{{ Arr::get($customer_event, 'booking.customer.' . $field) }}"
                />
                
            @endswitch
            @error("booking.customer.$field")
                <p class="text-red-600">{{ $message }}</p>
            @enderror
        </x-forms.form-group>    
        @endforeach
    </fieldset>

    <fieldset>
        <legend>Location/Address</legend>
        @foreach (['line1' => 'Line #1', 'line2' => 'Line #2', 'state' => 'State'] as $attr => $label )
        <x-forms.form-group :field="'booking[location][' . $attr . ']'" :errors="$errors">
            <x-slot:label>
                {{ $label }}
            </x-slot:label>
            @switch($attr)
                @case('state')
                    <x-select :required="true" :text_as_value="true" :name="'booking[location][state]'" :options="config('region.states')" :value="Arr::get($customer_event, 'booking.location.sate', 'Lagos')">

                    </x-select>
                    @break
                @default
                <input type="text" @class([
                    'control', 'w-full', 'md:w-4/5',
                    'error' => $errors->has("booking.location.$attr")
                ])  @required($attr == 'line1') name="booking[location][{{ $attr }}]" class="control w-full md:w-4/5" placeholder="Enter {{ $label }}" 
                    value="{{ Arr::get($customer_event, 'booking.location.' . $attr) }}" />
                    @error("booking.location.$attr")
                        <p class="text-red-600">{{ $message }}</p>
                    @enderror
            @endswitch
        </x-forms.form-group>    
        @endforeach
    </fieldset>

    <div class="mt-4 mb-20 flex flex-row justify-end items-center gap-4">
        <a class="link uppercase" href="{{ $cancel_url ?? route('admin.customer_events.index') }}">
            Cancel
        </a>
        <button type="submit" class="btn">
            Save event
        </button>
    </div>

</form>