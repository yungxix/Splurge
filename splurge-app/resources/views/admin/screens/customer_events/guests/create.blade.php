@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;

    
    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Customer Events', 'url' => route('admin.customer_events.index')],
        ['text' => Str::limit($customer_event->name, 30), 'url' => route('admin.customer_events.show', $customer_event)],
        ['text' => 'New Guest', 'current' => true, 'active' => true, 'url' => '#']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'New Guest')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'New Guest'])
    <section class="container mx-auto">
        <div class="md:flex flex-row  my-8">
            <div class="md:w-1/2">
                @include('admin.screens.customer_events.guests.form', ['url' => route('admin.customer_event_detail.guests.store', $customer_event)])
            </div>
            <div class="p-4 bg-gray-50 rounded-md shadow-md md:w-1/2">
                @include('admin.screens.customer_events.snapshot', ['include_name' => true])
            </div>
        </div>
    </section>
@endsection