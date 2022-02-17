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
        <div class="lg:flex flex-row">
            <div class="lg:w-2/3 px-4">
                <article>
                    
        
                    <div class="">
                        {{ HtmlHelper::toParagraphs($post->description, 'text-justify') }}
            
                    @foreach ($post->items as $item)
                        <section>
                            @unless (empty($item->image_url))
                            <div class="grid grid-cols-2 mb-1">
                                <div class="bg-pink-500">
                                    <img class="block w-full" src="{{ $item->image_url }}" />
                                </div>
                                <div class="bg-white p-2">
                                    <h5 class="font-bold">{{ $item->name }}</h5>
                                </div>
                            </div>
                            @else
                            <h5 class="font-bold mb-1">{{ $item->name }}</h5>    
                            @endunless
                            {{ HtmlHelper::toParagraphs($item->content, 'text-justify') }}
                        </section>
                    @endforeach
        
        
                    @unless ($post->taggables->isEmpty())
                        <div class="mt-4 p-2">
                            Tags: 
                            @foreach ($post->taggables as $t)
                                <span class="px-4 py-2 bg-pink-500 text-black rounded">
                                    {{ $t->tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endunless
                    </div>
            
                    
                </article>
            </div>
            <div class="lg:w-1/3 px-2 bg-gray-200">
                <x-widgets.other-posts :post-id="$post->id"></x-widgets.other-posts>
            </div>
        </div>
        
        
        
        
        
    </section>
@endsection