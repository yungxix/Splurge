@php
    use App\Support\HtmlHelper;

    $icon_class= "w-8 h-8 text-white";

    $icon_bg_class = "block rounded-full bg-splarge-800 w-12 flex flex-col items-center justify-center p-2";
@endphp
@props(['booking'])
<div>
    <a class="block link" href="{{ route('admin.bookings.show', $booking) }}">
        <h4 class="text-lg">
            Booking #{{ $booking->code }}
            <span class="text-sm text-gray-600 ml-8">
                {{$booking->serviceTier->service->name }} &mdash;
                {{$booking->serviceTier->name}}
            </span>
        </h4>
    </a>
    <div class="mb-4">
        <div class="{{ $icon_bg_class }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $icon_class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
        </div>
        <span>
            {{$booking->event_date->format('l jS \\of F Y') }}</span>
    </div>
    <div class="mb-4">
        <div class="{{ $icon_bg_class }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $icon_class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
              </svg>
        </div>
        {{ HtmlHelper::renderParagraphs($booking->description) }}
    </div>
    <div class="my-4">
        <div class="{{ $icon_bg_class }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $icon_class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
        </div>
        <p>
            {{$booking->customer->first_name}} {{$booking->customer->last_name}}
            
        </p>
        <p>
            E: <a href="mailto:{{ $booking->customer->email }}" class="link">
                {{ $booking->customer->email }}
            </a>
            &nbsp;&nbsp;
            P: <a href="tel:{{ $booking->customer->phone }}" class="link">
                {{ $booking->customer->phone }}
            </a>
        </p>
    </div>
    <div class="my-4">
        <div class="{{ $icon_bg_class }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $icon_class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
        </div>
        <div>
            <address>
                @unless (empty($booking->location->name))
                    <span class="block font-bold">
                        {{$booking->location->name}}
                    </span>
                @endunless
                {{$booking->location->line1}}<br />
                {{$booking->location->line2}}<br />
                @unless (empty($booking->location->zip))
                {{$booking->location->state}} - {{$booking->location->zip}}
                @else
                {{$booking->location->state}}
                @endunless
            </address>
        </div>
    </div>




</div>