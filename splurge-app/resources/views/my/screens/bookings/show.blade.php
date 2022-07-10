@php
    use App\Http\Resources\BookingResource;
    use App\Support\HtmlHelper;
    $breadCrumbItems = [
        [
            'text' => 'My Bookings',
            'url' => route('my.bookings.index')
        ],
        [
            'text' => 'Booking #' . $booking->code,
            'url' => '#'
        ]
    ];
@endphp
@extends('layouts.my')

@section('title', 'Booking #' . $booking->code)

@section('content')
    <x-breadcrumbs :items="$breadCrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Booking #' . $booking->code])
    <div class="bg-gray-100 pt-8 pb-12">
        <div id="my_app" class="container mx-auto">
            @include('my.screens.bookings.toolbar')
            <div id="app_root">
                <div class="flex flex-col items-center justify-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        Splurge.my.bookings.renderCombinedDetails(document.querySelector('#app_root'), {
            url: '{{ route('my.bookings.update', $booking) }}',
            booking: {{ Js::from((new BookingResource($booking))->resolve(app('request'))) }}
        });
    </script>
@endpush