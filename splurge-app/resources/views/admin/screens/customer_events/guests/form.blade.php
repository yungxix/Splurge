@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;

    $attributes = [
        'name' => [
            'label' => 'Name of guest',
            'required' => true,
            'widget' => 'text'
        ],
        'gender' => [
            'label' => 'Gender',
            'widget' => 'select',
            'widget_options' => [
                'options' => [
                    'Not specified',
                    'Male',
                    'Female',
                    
                ]
            ]
        ],
        'table_name' => [
            'label' => 'Table name or number',
            'widget' => 'text'
        ]
    ];

    if ($guest->id > 0) {
        $attributes = array_merge($attributes, [
            'attendance_at' => [
                'label' => 'Attended at',
                'widget' => 'time'
            ],
            'accepted' => [
                'label' => 'Accepted',
                'widget' => 'js_component',
                'widget_options' => [
                    'wrapper_id' => 'guest_accepted_host',
                    'options' => [
                        'value' => $guest->accepted,
                        'name' => 'guest[accepted]',
                    ],
                    'call' => 'Splurge.CustomerEvents.mountStandaloneAttachmentWidget'
                ]
            ],
            'presented' => [
                'label' => 'Presented',
                'widget' => 'js_component',
                'widget_options' => [
                    'wrapper_id' => 'guest_presented_host',
                    'options' => [
                        'value' => $guest->presented,
                        'name' => 'guest[presented]',
                    ],
                    'call' => 'Splurge.CustomerEvents.mountStandaloneAttachmentWidget'
                ]
            ]
        ]);

    }
@endphp
<form class="my-8 h-full"  method="POST" action="{{ $url }}">
    @csrf
    @isset($method)
        @method($method)
    @endisset

    <div class="h-full flex flex-col">
        <div class="flex-grow">
            @foreach ($attributes as $field => $options)
            <x-forms.form-group :field="'guest[' . $field . ']'" :errors="$errors">
                <x-slot:label>
                    {{ $options['label'] }}
                </x-slot:label>
                @switch(Arr::get($options, 'widget'))
                    @case('select')
                        <x-select class="control md:w-4/5" :name="'guest[' . $field . ']'"
                         :text_as_value="true" :options="Arr::get($options, 'widget_options.options')"
                          :value="Arr::get($guest, $field)"
                          :required="Arr::get($options, 'required', false)"></x-select>
                        @break 
                    @case('js_component')
                        <div id="{{ Arr::get($options, 'widget_options.wrapper_id') }}" class="mr-8">
                            <input type="text" value="..." readonly />
                        </div>
                        @break
                    @case('time')
                        <input type="time" class="control md:w-4/5 @error($field) error @enderror"  placeholder="Enter {{ Str::lower($options['label']) }}" name="guest[{{  $field }}]"
                         value="{{ $guest->getAttendanceTime() }}" @required(Arr::get($options, 'required', false)) />   
                         @error($field)
                             <p class="text-red-600">{{ $message }}</p>
                         @enderror 
                        @break
                    @default
                        <input type="text" class="control md:w-4/5 @error($field) error @enderror"  placeholder="Enter {{ Str::lower($options['label']) }}" name="guest[{{  $field }}]"
                         value="{{ Arr::get($guest, $field) }}" @required(Arr::get($options, 'required', false)) />   
                         @error($field)
                             <p class="text-red-600">{{ $message }}</p>
                         @enderror 
                @endswitch
            </x-forms.form-group>
            @endforeach
        </div>
        <div class="pr-8">
            <div class="gap-4 flex flex-row justify-end items-center my-2 mx-2">
                <a class="link" href="{{ $cancel_url ?? route('admin.customer_event_detail.guests.index', $customer_event) }}">
                    Cancel
                </a>
                <button class="btn" type="submit">
                    Save
                </button>
            </div>
        </div>
    </div>
</form>


@if ($guest->id > 0)

@push('scripts')
<script src="{{ mix('js/customer_events.js') }}"></script>
    <script>
        (function () {
            "use strict";

            @foreach ($attributes as $attr => $options)
                @if (Arr::get($options, 'widget') === 'js_component')
                    @php
                        $js_options = array_merge(Arr::get($options, 'widget_options.options', []), [
                            'attribute' => $attr,
                            'name' => "guest[$attr]"
                        ]);
                    @endphp
                    {{ Arr::get($options, 'widget_options.call') }}(
                        document.getElementById('{{ Arr::get($options, 'widget_options.wrapper_id') }}'),
                        {!! @Js::from($js_options) !!}
                    );
                @endif
            @endforeach
        })();
    </script>
@endpush

@endif