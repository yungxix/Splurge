@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;

    
    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Customer Events', 'url' => route('admin.customer_events.index')],
        ['text' => 'New Event', 'url' => '#', 'current' => true, 'active' => true]
    ];
@endphp
@extends('layouts.admin')

@section('title', 'New Customer Event')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'New Customer Event'])
    <section class="container mx-auto">
        @include('admin.screens.customer_events.form', ['url' => route('admin.customer_events.store')])
    </section>
@endsection