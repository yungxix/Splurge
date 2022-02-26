@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'Gallery')


@section('content')
    
    <x-admin.gallery-header :gallery="$gallery">
        <x-slot:links>
            <span id="upload-trigger"></span>
            <a class="link" href="{{ route('admin.gallery_detail.gallery_items.edit', ['gallery' => $gallery->id, 'gallery_item' => $gallery_item->id]) }}">
                Edit page
            </a>
        </x-slot:links>
        <div>
            <h4 class="text-2xl">Page: &quot;{{ $gallery_item->heading }}&quot;</h4>
            <div class="mt-4 text-gray-700">
                {{ HtmlHelper::toParagraphs($gallery_item->content) }}
            </div>
        </div>
    </x-admin.gallery-header>
    <section class="container mt-4 mx-auto">
        <h4 class="text-lg">Pictures</h4>
        <div class="grid grid-cols-2 md:grid-cols-4">
            @foreach ($gallery_item->mediaItems as $item)
                <div class="p-4">
                    <div class="relative">
                        <x-medium-view :model="$item" class="w-full rounded-md overflow-clip"></x-medium-view>
                        <div class="flex flex-row justify-end">
                            <a href="#" data-action="{{ route('admin.gallery_detail.gallery_item.media.destroy', ['gallery' => $gallery->id, 'gallery_item' => $gallery_item->id, 'medium' => $item->id]) }}" class="text-red-900 delete-medium hover:text-red-600">
                                <svg  xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                </div>
            @endforeach
        </div>
    </section>

    @push('scripts')
        <form id="delete_medium_form" class="hidden w-0 h-0" method="POST">
            <input type="hidden" name="_method" value="DELETE" />
            @csrf
        </form>
        <script>
            document.querySelectorAll('.delete-medium').forEach(function (el) {
                el.onclick = function (e) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to delete this item?')) {
                        return false;
                    }
                    var form = document.getElementById('delete_medium_form');
                    form.action = el.getAttribute('data-action');
                    form.submit();
                    return false;
                }
            });

            Splurge.admin.uploader.renderTrigger(document.querySelector('#upload-trigger'), {
                text: 'Add picture',
                title: 'Picture upload',
                description: 'Add a picture to this gallery page',
                reloadOnComplete: true,
                uploadOptions: {
                    url: '{{ route('admin.gallery_detail.gallery_item.media.store', ['gallery' => $gallery->id, 'gallery_item' => $gallery_item->id]) }}',
                    owner: {
                        id: {{ $gallery_item->id }},
                        type: 'gallery_item'
                    }
                }
            });

        </script>

    @endpush
@endsection