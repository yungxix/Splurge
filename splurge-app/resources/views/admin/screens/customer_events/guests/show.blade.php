@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
    use App\Http\Resources\CustomerEventGuestResource;

    $title = 'Guest Details';
    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Customer Events', 'url' => route('admin.customer_events.index')],
        ['text' => Str::limit($customer_event->name, 30), 'url' => route('admin.customer_events.show', $customer_event)],
        ['text' => $title, 'current' => true, 'active' => true, 'url' => '#']
    ];
    $data = (new CustomerEventGuestResource($guest))->resolve(app('request'));
@endphp
@extends('layouts.admin')

@section('title', $title)


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => $title])
    <section class="container mx-auto">
        <div class="my-8 md:flex flex-row">
            <div class="md:w-1/2">
                <div id="guest-app">
                    <div class="pt-12">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 animate-spin mx-auto">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                </div>  
            </div>
            <div class="p-4 bg-gray-50 rounded-md shadow-md md:w-1/2">
                @include('admin.screens.customer_events.snapshot', ['include_name' => true])
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ mix('js/customer_events.js') }}"></script>
    <script>
        (function () {
            "use strict";
            
            Splurge.CustomerEvents.mountGuestView(
                document.getElementById("guest-app"), {
                    baseUrl: '{{ route('admin.customer_events.show', $customer_event) }}',
                    editable: {{ $customer_event->isPast() ? 'false' : 'true' }},
                    data: {{ Js::from($data) }}
                }) ;
        })();
    </script>
@endpush