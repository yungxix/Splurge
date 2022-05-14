@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Bookings', 'url' => '#'],
    ];
@endphp

@extends('layouts.admin')

@section('title', 'Bookings')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Bookings', 'sub_title' => $custom_title])

    <section class="container mx-auto">
        @include("admin.screens.bookings.partials.search_form")
        <hr class="my-4" />
        @if ($bookings->isEmpty())
            <div class="text-center py-16">
                <em>
                    No booking was found
                </em>
            </div>
        @else
        <div class="grid grid-cols-1 gap-y-14 divide-y divide-gray-400">
            @foreach ($bookings as $item)
            <div class="md:flex flex-row justify-start items-start">
                <div class="flex-1">
                    <a href="{{ route('admin.bookings.show', $item) }}" class="link font-bold block mb-4">
                        #{{$item->code}}
                    </a>
                    <a href="{{ route('admin.bookings.show', $item) }}" class="link block text-2xl">
                        {{$item->customer->first_name}}
                        {{$item->customer->last_name}}
                    </a>
                    <h4 class="text-xl my-2 text-gray-600">
                        {{$item->serviceTier->service->name}}
                        <span class="text-lg">
                            {{$item->serviceTier->name}}
                        </span>
                    </h4>
                    {{HtmlHelper::toParagraphs($item->description)}}
                    <p class="mt-8 text-gray-700 flex flex-row justify-end items-center flex-wrap text-sm">
                        Posted {{$item->created_at->diffForHumans()}}
    
                        @include("admin.partials.payment-status", ['booking' => $item, 'payments' => $payments])

                        <a class="link ml-8" href="{{ route('admin.bookings.show', $item) }}">
                            Details
                        </a>
                    </p>
                </div>
                <div class="md:w-60 md:flex flex-col items-end justify-end pt-4">
                    <div class="bg-splarge-600 w-12 h-12 rounded-full flex flex-col justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    
                    <div>{{ $item->event_date->format("l F j, Y") }}</div>
                    <div class="text-gray-700">
                        ({{$item->event_date->diffForHumans()}})
                    </div>
                </div>
            </div>   
            @endforeach
            <div class="flex flex-row justify-end mb-8">
                {{ $bookings->links() }}
            </div>
        </div>    
        @endif
        
    </section>
    

@endsection