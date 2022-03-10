@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Gallery')


@section('content')
    @include('partials.page-header', ['title' => 'Gallery'])
    <section class="container mx-auto">
        <div class="flex flex-row justify-end p-4 items-center mb-4">
            <a class="btn" href="{{ route('admin.gallery.create') }}">
                New Gallery
            </a>
        </div>
        <div>
            @foreach ($gallery as $item)
            <div class="stacked">
                <a href="{{ route('admin.gallery.show', ['gallery' => $item->id]) }}" class="image-link">
                    <figure>
                        <img src="{{ splurge_asset($item->image_url) }}" />
                    </figure>
                </a>
                <div class="grow p-4">
                    <a href="{{ route('admin.gallery.show', ['gallery' => $item->id]) }}" class="link">
                        <h4 class="text-lg font-bold">{{ $item->caption }}</h4>
                    </a>
                    {{HtmlHelper::toParagraphs(Str::limit($item->description, 250, '...'))}}
                    <p class="mt-8 text-gray-700 text-right text-sm">
                        {{$item->items_count}} {{ Str::plural('page', $item->items_count) }},&nbsp;
                        {{$item->media_items_count}} {{ Str::plural('picture', $item->media_items_count) }},&nbsp;
                        created {{ $item->created_at->diffForHumans() }}.
                        @if (Auth::id() === $item->author_id)
                            <em>Uploaded by me</em>
                        @elseif (!is_null($item->author))
                            <em>Uploaded by {{ $item->author->name }}</em>
                        @endif
                    </p>
                </div>
                <div class="pt-4 pr-4">
                    @include('admin.screens.gallery.partials.gallery-item-actions', ['model' => $item])
                </div>
            </div>        
            @endforeach
            <div class="flex flex-row justify-end mb-8">
                {{ $gallery->links() }}
            </div>
        </div>
    </section>
@endsection