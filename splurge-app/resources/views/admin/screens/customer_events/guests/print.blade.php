@extends('layouts.plain')

@php
    $attributes = [
        'table_name' => [
            'label' => 'Table'
        ],
        'menuPreferences' => [
            'label' => 'Menu Preferences',
            'view' => 'admin.customer_events.guests.print.menu_preferences'
        ],
        'accepted' => [
            'label' => 'List of items collected by guest',
            'view' => 'admin.customer_events.guests.print.attachments'
        ],
        'presented' => [
            'label' => 'List of items presented by guest',
            'view' => 'admin.customer_events.guests.print.attachments'
        ]
    ];
@endphp

@section('title')
Print Guests
@endsection

@section('content')
    @include('partials.page-header', ['title' => Str::limit($customer_event->name, 50)])
    <div class="mb-4 bg-gray-100 p-4">
        <div class="container mx-auto">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                </svg>
                {{ $customer_event->event_date->format('j')}}  

            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <strong class="block">
                  {{ $customer_event->booking->customer->full_name}}
                </strong>
                <div>E: {{ $customer_event->booking->customer->email }},  P: {{ $customer_event->booking->customer->phone }}</div>

            </div>
           
            @if (!is_null($customer_event->booking->location))
                <div class="mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                      </svg>
                      <address>
                        {{ $customer_event->booking->location->line1 }}<br />
                        {{ $customer_event->booking->location->line2 }}<br />
                        {{ $customer_event->booking->location->state }} {{ $customer_event->booking->location->zip }}<br /
                      </address>
                </div>
            @endif
        </div>
    </div>
    <div class="container mx-auto">
        @if ($guests->isEmpty())
    <p class="text-center text-xl">
        <em>
            No guest has been registered for this event yet
        </em>
    </p>
    @endif
   <div class="grid grid-cols-2 md:grid-cols-4 gap-1">
    
        @foreach ($guests as $guest)
        <div class="rounded shadow">
            @if (!empty($guest->barcode_image_url))
            <div class="w-full text-center py-2 border-b border-splarge-900">
                <img alt="Guest barcode" class="block" src="{{ splurge_asset($guest->barcode_image_url) }}"/>
            </div>
            @endif
            <div>
                <h4 class="text-lg">
                    {{$guest->name}}
                </h4>

                @foreach ($attributes as $name => $options)
                    <div class="mb-4">
                        <div class="font-bold font-mono">
                            {{$options['label']}}
                        </div>
                        @if (isset($options['view']))
                            @include($options['view'], ['guest' => $guest, 'attribute' => $name])
                        @elseif (empty($guest[$name]))
                            <p>
                                <em>N/A</em>
                            </p>
                        @else
                            {{ $guest[$name] }}
                        @endif
                    </div>
                @endforeach

                
            </div>
        </div>
        @endforeach
   </div>
    </div>
    
   <div class="mb-10">&nbsp;</div>
   <div>
        <div class="float-right">
            {{$guests->links()}}
        </div>
        <br  class="clear-both" />
   </div>
@endsection

@push('scripts')
    <script>
        window.print();
    </script>
@endpush