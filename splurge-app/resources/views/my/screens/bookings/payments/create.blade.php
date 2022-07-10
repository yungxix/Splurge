@php
    use App\Http\Resources\BookingResource;
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;

    $breadCrumbItems = [
        [
            'text' => 'My Bookings',
            'url' => route('my.bookings.index')
        ],
        [
            'text' => 'Booking #' . $booking->code,
            'url' => route('my.bookings.show', $booking)
        ], [
            'text' => 'New Payment',
            'url' => '#'
        ]
    ];
@endphp
@extends('layouts.my')

@section('title', 'New Payment')

@section('content')
    <x-breadcrumbs :items="$breadCrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'New Payment for Booking #' . $booking->code])
    <div class="bg-gray-100 pt-8 pb-12">
        <div id="my_app" class="container mx-auto">
            @include('my.screens.bookings.toolbar')
            <div class="md:flex flex-row">
                <div class="md:w-1/2">
                    <form method="GET" class="mt-4" action="{{ route('my.booking_details.payments.accept', $booking) }}">
                        <input type="hidden" name="status" value="success" />
                        <input type="hidden" name="reference" value="{{ Str::random(19) }}" />
                        <input type="hidden" name="amount" value="{{ $balance }}" />
                        <input type="hidden" name="spt" value="{{ $token }}" />
                        <p class="bg-red-200 rounded-md text-red-800 p-4">
                            WARNING! This is a simulation of callback url from payment processor like paystack
                        </p>
                        <button type="submit" class="btn w-full mt-4">
                            Pay {{ HtmlHelper::renderAmount($balance)}}
                        </button>
                    </form>        
                </div>
                <div class="md:w-1/2" id="app_root">
                    <div class="md:pl-4">
                        @include('my.screens.bookings.snapshort')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
