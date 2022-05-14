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
            'url' => route('my.bookings.show', $booking)
        ], [
            'text' => 'Booking Messages',
            'url' => route('my.booking_details.messages.index', ['booking' => $booking->id])
        ], [
            'text' => 'Details',
            'url' => '#'
        ]
    ];

    if (!function_exists('splurge_resolve_email_contact')) {
        function splurge_resolve_email_contact($value) {
            if ('customer' === $value) {
                return 'Me';
            }
            if ('company' === $value) {
                return config('app.name');
            }
            return $value;
        }
    }
@endphp
@extends('layouts.my')

@section('title', 'Messages')

@section('content')
    <x-breadcrumbs :items="$breadCrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Booking #' . $booking->code . ' Message'])
    <div class="bg-gray-100 pt-8 pb-12">
        <div id="my_app" class="container mx-auto">
            @include('my.screens.bookings.toolbar')
            <div class="md:flex flex-row">
                <div class="md:w-1/2">
                    <x-detail-container :label="'From'" :vertical="true">
                        {{ splurge_resolve_email_contact($message->sender) }}
                    </x-detail-container>
                    <x-detail-container :label="'To'" :vertical="true">
                        {{ splurge_resolve_email_contact($message->receiver) }}
                    </x-detail-container>
                    <x-detail-container :label="'Subject'" :vertical="true">
                        {{$message->subject}}
                    </x-detail-container>
                    <p class="text-right">
                        {{$message->created_at->diffForHumans()}}
                    </p>
                    <iframe class="w-full mt-4" height="600" src="{{ route('my.booking_details.messages.content', ['booking' => $booking->id, 'message' => $message->id]) }}">

                    </iframe>
                </div>
                <div class="md:w-1/2">
                    <div class="md:pl-4 pt-8">
                        @include('my.screens.bookings.snapshort')
                    </div>
                </div>
            </div>     
        </div>
    </div>
@endsection
