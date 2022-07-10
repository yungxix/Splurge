@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'All Services', 'url' => route('admin.services.index')],
        ['text' => 'Details', 'url' => '#']
    ];
@endphp
@extends('layouts.admin')


@section('title', 'Package Details')


@section('content')

    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => sprintf('%s Package', $service->name)])
    {{-- @include('admin.screens.services.partials.header', ['service' => $service]) --}}
    {{-- <hr class="mb-4 w-3/4 mx-auto" /> --}}
    <div class="container mx-auto">
        <div class="hidden md:block">
            @include('admin.screens.services.partials.show_standard', ['service' => $service])
        </div>
    
        <div class="md:hidden">
            @include('admin.screens.services.partials.show_mobile', ['service' => $service])
        </div>
    </div>
    
    
    <div class="container mx-auto">
        <div class="my-8">
            @include('admin.partials.tags-assignment', ['taggable' => ['id' => $service->id, 'type' => 'service']])
        </div>
    </div>
@endsection