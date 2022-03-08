@php
    use Illuminate\Support\Str;

@endphp
@unless ($posts->isEmpty())
<div class="text-gray-500 text-base">
    <h4 class="text-lg font-semibold mb-2">{{ $title }}</h4>
    <div class="">
        @foreach ($posts as $post)
            @if (!$loop->first)
                <hr />
            @endif
            @if (empty($post->image_url) && empty($post->thumbnail_image_url) )
                
                <a href="{{ route('events.show', ['post' => $post->id]) }}" class="block my-4 p-2 hover:bg-splarge-700  hover:text-white">
                    <h5 class="font-semibold">{{ $post->name }}</h5>
                    <p>
                        {{ Str::limit($post->description, 120, '...') }}
                    </p>
                </a>
            @else
                <a href="{{ route('events.show', ['post' => $post->id]) }}" class="block my-4 p-2 hover:bg-splarge-700  hover:text-white">
                    <div class="grid grid-cols-2">
                        <div>
                            <img class="w-full rounded-tl-md"  alt="{{ $post->name  }} image" src="{{ asset($post->thumbnail_image_url ?: $post->image_url) }}" />
                        </div>
                        <div class="pl-2">
                            <h5 class="font-semibold">{{ $post->name }}</h5>
                            <p>
                                {{ Str::limit($post->description, 90, '...') }}
                            </p>
                        </div>
                    </div>    
                </a>
            @endif
            
        @endforeach
    </div>
    
    <p class="text-right mb-4">
        <a href="{{ route('events.index') }}">More&hellip;</a>
    </p>
</div>
@endunless