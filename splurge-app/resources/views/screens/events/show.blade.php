@php
    use Illuminate\Support\Str;
    use App\Support\HtmlHelper;
@endphp
@extends(config('view.defaults.layout'))

@section('title', Str::limit($post->name, 30))

@section('content')
    <section class="container mx-auto">
        @unless (empty($post->image_url))
            <figure class="w-full  mt-2 lg:mt-4 overflow-clip">
                <img class="block mx-auto" alt="{{ $post->name }} image" src="{{ asset($post->image_url) }}" />
            </figure>
            
        @endunless
        <h1 class="uppercase mb-2 text-lg lg:text-4xl text-center mt-2">{{ $post->name }}</h1>    
            <div class="mb-2 py-2 text-gray-700 text-sm font-serif text-right">
            @if (!is_null($post->author))
                <span class="mr-8">
                By {{ $post->author->name }}
                </span>
            @endif
            <em>{{ $post->created_at->diffForHumans() }}</em>
        </div>    
        <div class="md:flex flex-row">
            <div class="md:w-2/3 md:px-4 pb-8">
                <article>
                    
        
                    <div class="">
                        {{ HtmlHelper::toParagraphs($post->description, 'text-justify') }}
            
                        @foreach ($post->items as $item)
                            <x-post-item :post_item="$item"></x-post-item>
                        @endforeach
            
            
                        <x-tags :model="$post"></x-tags>
                    </div>
            
                    
                </article>
            </div>
            <div class="md:w-1/3 px-2 bg-gray-200">
                <x-widgets.other-posts :post-id="$post->id"></x-widgets.other-posts>
            </div>
        </div>
        
        
        
        
        
    </section>
@endsection