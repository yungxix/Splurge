@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;

    
    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Customer Events', 'url' => route('admin.customer_events.index')],
        ['text' => Str::limit($customer_event->name, 30), 'url' => route('admin.customer_events.show', $customer_event)],
        ['text' => 'Edit Guest', 'current' => true, 'active' => true, 'url' => '#']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Edit Guest')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Edit Guest'])
    <section class="container mx-auto">
        <div class="md:flex flex-row  my-8">
            <div class="md:w-1/2">
                @include('admin.screens.customer_events.guests.form', ['method' => 'PUT', 'cancel_url' => route('admin.customer_events.show', $customer_event), 'url' => route('admin.customer_event_detail.guests.update', ['customer_event' => $customer_event->id, 'guest' => $guest->id])])
            </div>
            <div class="p-4 bg-gray-50 rounded-md shadow-md md:w-1/2">
                @include('admin.screens.customer_events.snapshot', ['include_name' => true])
            </div>
        </div>
    </section>
@endsection