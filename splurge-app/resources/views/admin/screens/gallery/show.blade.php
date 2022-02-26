@extends('layouts.admin')

@section('title', 'Gallery')


@section('content')
    <x-admin.gallery-header :gallery="$gallery"></x-admin.gallery-header>
    <hr class="mb-4 w-3/4 mx-auto" />
    @if ($items->isEmpty())
    <div class="container mx-auto">
        <div class="mx-auto flex flex-row items-center justify-start  mb-8 p-4 bg-slate-200 rounded-md shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 flex-initial" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-lg grow">
                <p class="leading-6 text-center">
                    The next thing is to add pages to this gallery. <a class="link" href="{{ route('admin.gallery_detail.gallery_items.create', ['gallery' => $gallery->id]) }}">
                        Add your first page
                    </a>
                </p>
            </div>
        </div>
    </div>
    @else
    <section class="container mx-auto">
        <h5 class="">Pages</h5>
        <div>
            <x-simple-table :columns="['Page', 'Heading', 'Number of pictures', '', '']">
                <x-slot:footer>
                    <div class="flex justify-end items-center">
                        {{ $items->links() }}
                    </div>
                </x-slot:footer>

                @foreach ($items as $page)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            #{{ $loop->index + $items->currentPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a class="link" href="{{ route('admin.gallery_detail.gallery_items.show', ['gallery' => $gallery->id, 'gallery_item' => $page->id]) }}">
                                {{ $page->heading }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $page->media_items_count }}
                        </td>
                        <td>
                            @include('admin.screens.gallery.partials.items.table-actions', ['model' => $page])
                        </td>
                    </tr>
                @endforeach
                
            </x-simple-table>

            
        </div>
        
    </section>

    @endif
@endsection