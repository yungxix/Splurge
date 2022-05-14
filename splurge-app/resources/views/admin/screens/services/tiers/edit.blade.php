@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'All Services', 'url' => route('admin.services.index')],
        ['text' => $service->name . ' Details', 'url' => route('admin.services.show', $service)],
        ['text' => 'Tiers', 'url' => route('admin.service_detail.tiers.index', $service)],
        ['text' => 'Edit Tier']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Edit Service Tier')


@section('content')
<x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => $service->name, 'sub_title' => 'Edit Service Tier'])
    
    <hr class="mb-4 w-3/4 mx-auto" />

    <div class="container mx-auto">
        @include('admin.screens.services.tiers.form', ['tier' => $tier, 'service' => $service])
    </div>
@endsection