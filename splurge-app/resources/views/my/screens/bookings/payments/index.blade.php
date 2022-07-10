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
            'text' => 'Payments',
            'url' => '#'        
        ]
    ];
    $tableColumns = ['#', 'Date', 'Amount', 'Statement'];
@endphp
@extends('layouts.my')

@section('title', 'Booking #' . $booking->code)

@section('content')
    <x-breadcrumbs :items="$breadCrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Booking #' . $booking->code])
    <div class="bg-gray-100 pt-8 pb-12">
        <div id="my_app" class="container mx-auto">
            @include('my.screens.bookings.toolbar')
            <div class="md:flex flex-row">
                <div class="md:w-1/2">
                    <x-simple-table :columns="$tableColumns">
                        @foreach ($booking->payments as $payment)
                            <tr>
                                <td class="px-4 py-2">
                                    {{$payment->code}}
                                </td>
                                <td class="px-4 py-2">
                                    {{$payment->created_at->format('M d, Y @ h:m a')}}
                                </td>
                                <td class="px-4 py-2">
                                    {{HtmlHelper::renderAmount($payment->amount)}}
                                </td>
                                <td class="px-4 py-2 overflow-ellipsis">
                                    {{$payment->statement}}
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