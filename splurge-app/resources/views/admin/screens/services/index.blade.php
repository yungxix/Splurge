@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'All Services', 'url' => route('admin.services.index')]
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Services')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Services'])
    <section class="container mx-auto">
        <div class="flex flex-row justify-end p-4 items-center mb-4">
            <a class="btn" href="{{ route('admin.services.create') }}">
                New service
            </a>
        </div>
        <div>
            @foreach ($services as $item)
            <div class="stacked">
                <a href="{{ route('admin.services.show', ['service' => $item->id]) }}" class="image-link">
                    <figure>
                        <img src="{{ splurge_asset($item->thumbnail_image_url ?: $item->image_url) }}" />
                    </figure>
                </a>
                <div class="grow p-4">
                    <a href="{{ route('admin.services.show', $item) }}" class="link">
                        <h4 class="text-lg font-bold">{{ $item->name }}</h4>
                    </a>
                    {{Str::limit(strip_tags($item->description), 250, '...')}}
                </div>
                <div class="pt-4 pr-4">
                    @include('admin.screens.services.partials.item-actions', ['model' => $item])
                </div>
            </div>        
            @endforeach
        </div>
    </section>
@endsection