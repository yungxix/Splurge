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
            'url' => '#'
        ]
    ];
    $tableColumns = ['From', 'To', 'Subject', ''];

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
    @include('partials.page-header', ['title' => 'Booking #' . $booking->code . ' Messages'])
    <div class="bg-gray-100 pt-8 pb-12">
        <div id="my_app" class="container mx-auto">
            @include('my.screens.bookings.toolbar')
            <div class="md:flex flex-row">
                <div class="md:w-1/2">
                    <x-simple-table :columns="$tableColumns">
                        @foreach ($messages as $message)
                            <tr>
                                <td class="px-2 py-1">
                                    <a class="link" href="{{ route('my.booking_details.messages.show', ['booking' => $booking->id, 'message' => $message->id]) }}">
                                        {{splurge_resolve_email_contact($message->sender)}}
                                    </a>
                                </td>
                                <td class="px-2 py-1">
                                    {{splurge_resolve_email_contact($message->receiver)}}
                                </td>
                                <td class="px-2 py-1 overflow-ellipsis">
                                    {{$message->subject}}
                                </td>
                                <td class="px-2 py-1 overflow-ellipsis">
                                    {{$message->created_at->diffForHumans()}}
                                </td>
                            </tr>
                        @endforeach
                    </x-simple-table>
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
