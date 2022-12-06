@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;

    
    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Customer Events', 'url' => route('admin.customer_events.index')],
        ['text' => 'Event detail', 'url' => route('admin.customer_events.show', $customer_event)],
        ['text' => 'Edit', 'url' => '#', 'current' => true, 'active' => 'true']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Edit Customer Event')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Edit Customer Event'])
    <section class="container mx-auto">
        @include('admin.screens.customer_events.form', ['url' => route('admin.customer_events.update', $customer_event), 'method' => 'PUT', 'cancel_url' => route('admin.customer_events.show', $customer_event)])
    </section>
@endsection