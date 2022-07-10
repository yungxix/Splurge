@php
    use Illuminate\Support\Str;
    use App\Support\HtmlHelper;
@endphp

@extends('layouts.my')

@section('title', 'My Bookings')

@section('content')
    @include('partials.page-header', ['title' => 'My Bookings'])
    <div class="bg-gray-100 pb-12">
        <div class="container mx-auto">
            <div class="flex flex-col divide-y items-stretch">
                @foreach ($bookings as $booking)
                   <div class="block mx-4 my-8 p-4 bg-white rounded-md shadow-md">
                    <div class="flex flex-row">
                        <div class="w-44">
                            <div class="rounded-full w-12 h-12 flex flex-col items-center justify-center bg-splarge-800 p-4 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-800">
                                {{ $booking->event_date->format('l') }}<br />
                                {{ $booking->event_date->format('F d') }}<br />
                                {{ $booking->event_date->format('Y') }}
                            </p>
                        </div>
                        <div>
                            <a class="link" href="{{ route('my.bookings.show', $booking) }}">
                                <h4 class="text-2xl text-gray-900 mb-2">
                                    {{$booking->serviceTier->service->name}} service
                                </h4>
                            </a>
                            <h4 class="text-xl text-gray-700 mb-8">
                                {{$booking->serviceTier->name}} tier
                            </h4>
                            <div class="text-gray-600">
                                {{HtmlHelper::renderParagraphs($booking->description)}}
                            </div>
                            <p class="text-right mt-8">
                                <a class="link" href="{{ route('my.bookings.show', $booking) }}">
                                    Details
                                </a>
                            </p>
                        </div>
                    </div>
                </div> 
                @endforeach
            </div>
        </div>

        
       
    </div>
    
@endsection