@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'Services')


@section('content')
    @include('partials.page-header', ['title' => 'Services'])
    <section class="container mx-auto">
        <div class="flex flex-row justify-end p-4 items-center mb-4">
            <a class="btn" href="{{ route('admin.services.create') }}">
                New service
            </a>
        </div>
        <div>
            @foreach ($services as $item)
            <div class="md:flex flex-row mb-4 divide-y divide-slate-500 hover:bg-slate-400">
                <a href="{{ route('admin.services.show', ['service' => $item->id]) }}" class="m-4 block rounded-md shadow-md w-36 md:w-1/3 overflow-clip">
                    <figure>
                        <img src="{{ splurge_asset($item->thumbnail_image_url ?: $item->image_url) }}" />
                    </figure>
                </a>
                <div class="grow p-4">
                    <a href="{{ route('admin.services.show', $item) }}" class="link">
                        <h4 class="text-lg font-bold">{{ $item->name }}</h4>
                    </a>
                    {{HtmlHelper::toParagraphs(Str::limit($item->description, 250, '...'))}}
                </div>
                <div class="pt-4 pr-4">
                    @include('admin.screens.services.partials.item-actions', ['model' => $item])
                </div>
            </div>        
            @endforeach
        </div>
    </section>
@endsection