@php
    use App\Support\HtmlHelper;
@endphp

@extends('layouts.admin')

@section('title', 'Posts')


@section('content')
    @include('partials.page-header', ['title' => 'Posts Events'])
    <section class="container mx-auto">
        <x-search-form :url="route('admin.posts.index')"></x-search-form>
        <div class="flex flex-row justify-end p-4 items-center mb-4">
            <a class="btn" href="{{ route('admin.posts.create') }}">
                New Post/Event
            </a>
        </div>
        <div>
            @foreach ($posts as $item)
            <div class="stacked">
                <a href="{{ route('admin.posts.show', $item) }}" class="image-link">
                    <figure>
                        <img src="{{ splurge_asset($item->image_url) }}" />
                    </figure>
                </a>
                <div class="grow p-4">
                    <a href="{{ route('admin.posts.show', $item) }}" class="link">
                        <h4 class="text-lg font-bold">{{ $item->name }}</h4>
                    </a>
                    {{HtmlHelper::toParagraphs(Str::limit($item->description, 250, '...'))}}
                    <p class="mt-8 text-gray-700 text-right text-sm">
                        Created 
                        @if (Auth::id() === $item->author_id)
                            <em>by me</em>
                        @elseif (!is_null($item->author))
                            <em>by {{ $item->author->name }}</em>
                        @endif
                        {{ $item->created_at->diffForHumans() }}.
                    </p>
                </div>
                <div class="pt-4 pr-4">
                    @include('admin.screens.posts.partials.item-actions', ['model' => $item])
                </div>
            </div>        
            @endforeach
            <div class="flex flex-row justify-end mb-8">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
    

@endsection