@php
    use App\Support\HtmlHelper;
    use App\Http\Resources\BookingResource;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Bookings', 'url' => route('admin.bookings.index')],
        ['text' => 'Details', 'url' => '#']
    ];
@endphp
@extends('layouts.admin')


@section('title', sprintf("Booking #%s", $booking->code))



@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => sprintf('Booking #%s', $booking->code)])
    <div class="container mx-auto pt-4">
        @include("admin.screens.bookings.partials.service", ['serviceTier' => $booking->serviceTier])
        <div class="md:flex flex-row mt-8">
            <div class="md:w-2/3">
               <div class="mr-4" id="details_app">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 my-20 mx-auto animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
               </div>
               <div class="mb-12"></div>
            </div>
            <div class="md:w-1/3 p-2 bg-gray-100 rounded">
                <x-admin.booking-messages :booking="$booking" :per-page="5"></x-admin.booking-messages>
                <x-admin.widgets.recent-bookings :limit="3"></x-admin.widgets.recent-bookings>
            </div>
        </div>

    </div>
@endsection

@push("scripts")
    <script>
        Splurge.admin.bookings.renderDetails(document.getElementById("details_app"), {
            url: "{{ route("admin.bookings.update", $booking) }}",
            booking: {{{ Js::from((new BookingResource($booking))->resolve(app('request'))) }}}
        })
    </script>
@endpush