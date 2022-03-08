@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
    use App\Http\Resources\GalleryResource;
@endphp
@extends(config('view.defaults.layout'))

@section('title', 'Gallery')

@section('body_class', 'has-collage')


@section('content')
<section id="gallery" class="bg-splurge-900 text-gray-800 py-8 md:py-12">
    <div class="galla">
        <h5 class="text-center text-3xl mb-4">LUXURY WEDDINGS IMAGE GALLERY</h5>
        <p class="text-center"> Thank you to all the talented photographers we work with who so beautifully capture the celebrations we plan and produce.<br>
             We hope you love their imagery as much as we do.....</p>
    </div>
      
</section>



@foreach ($galleries as $gallery)
    @if ($gallery->items->isEmpty())
        @continue
    @endif

    @if (empty($gallery->image_url))
        <div class="container mx-auto">
            <h4 class="text-xl mb-4 font-bold uppercase">{{ $gallery->caption }}</h4>
        </div>
    @else
    <div class="flex flex-row justify-center">
        <figure class="max-h-56 block overflow-clip">
            <img class="block shadow-md" src="{{ asset($gallery->image_url) }}" alt="{{ $gallery->caption }} banner picture" />
            <figcaption>
                {{ $gallery->caption }}
            </figcaption>
        </figure>
    </div>
    
    @endif
    
    <section>
        <div class="bg-gray-200 p-4">
            {{ HtmlHelper::toParagraphs($gallery->description) }}
        </div>
       
        <div id="gallery_{{ $gallery->id }}_view" class="mt-2 mx-4">
            <div class="flex flex-row justify-center py-8">
                <svg class="animate-spin w-10 h-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
        </div>
        @push('scripts')
            <script>
                Splurge.gallery.render(document.getElementById('gallery_{{ $gallery->id }}_view'),
                {{{ Js::from((new GalleryResource($gallery))->resolve(app('request'))) }}}, {});
            </script>
        @endpush
        
    </section>
@endforeach


<div class="container mx-auto pt-8">
    <div class="flex flex-row items-center gap-x-2 justify-end">
        {{ $galleries->links() }}
    </div>
</div>
<p class="mb-6"></p>
@endsection