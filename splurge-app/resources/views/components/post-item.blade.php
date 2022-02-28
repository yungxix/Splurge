@props(['post_item', 'admin' => false])

@php
    use App\Support\HtmlHelper;
@endphp


<div class="mb-4">

    
@unless ($post_item->mediaItems->isEmpty())
<figure class="float-left mr-4">
    <img class="max-h-56" src="{{ splurge_asset($post_item->mediaItems->first()->url) }}"  />
    <figcaption>
        {{ $post_item->mediaItems->first()->name }}
    </figcaption>
</figure>
@endunless

<h4 class="text-xl text-center">
{{ $post_item->name }}
</h4>


@unless ($post_item->mediaItems->isEmpty())
<div class="clear-both"></div>    
@endunless

<div class="mt-2 leading-8 p-4">
{{ HtmlHelper::toParagraphs($post_item->content) }}

@can('admin')
    @if ($admin)
        <div class="flex flex-row items-center justify-end gap-4 p-4">
            <a href="{{ route('admin.post_detail.post_items.edit', ['post' => $post_item->post_id, 'post_item' => $post_item->id]) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                  </svg>
            </a>
            <x-delete-button :url="route('admin.post_detail.post_items.destroy', ['post' => $post_item->post_id, 'post_item' => $post_item->id])"></x-delete-button>
        </div>
    @endif   
@endcan
</div>


</div>
