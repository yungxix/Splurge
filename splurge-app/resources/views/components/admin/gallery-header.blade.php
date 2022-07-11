@php
    use Illuminate\Support\Str;
@endphp
@props(['gallery', 'preview_url' => null, 'show_stats' => false, 'hide_image' => false, 'caption' => null])
@include('partials.page-header', ['title' => $caption ?: $gallery->caption])

<section class="container mx-auto mb-4">
    @unless ($hide_image)
    <figure class="mx-auto">
        <img src="{{ splurge_asset($gallery->image_url) }}" />
        <figcaption>
            {{ $gallery->caption }}
        </figcaption>
    </figure>    
    @endunless
    
    <div class="flex flex-row justify-end p-4 gap-4 flex-wrap">
        <a class="link" href="{{ $preview_url ?: route('admin.gallery_detail.preview', ['gallery' => $gallery->id]) }}">
            Preview
        </a>
        <a class="link" href="{{ route('admin.gallery.edit', ['gallery' => $gallery->id]) }}">
            Edit
        </a>
        <a class="link" href="{{ route('admin.gallery_detail.gallery_items.create', ['gallery' => $gallery->id]) }}">
            Add photo collection/page
        </a>
        <a class="link" href="{{ route('admin.gallery.create') }}">
            New Gallery
        </a>
        {{ $links ?? '' }}
    </div>
    @if ($show_stats)
    <div class="border-t border-b p-4 bg-stone-200 mt-2">
        {{ $gallery->items_count }} {{ Str::plural('page', $gallery->items_count) }},&nbsp;&nbsp;
        {{ $gallery->media_items_count }} {{ Str::plural('picture', $gallery->media_items_count) }}
    </div>        
    @endif
    {{$slot}}
</section>