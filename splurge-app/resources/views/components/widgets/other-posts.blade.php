@php
    use Illuminate\Support\Str;

    $wrapper_class = 'horizontal' == $orientation ? 'flex flex-row overflow-x-auto divide-splarge-50' : '';
    $outside_class  = 'horizontal' == $orientation ? 'text-center' : '';


@endphp
@unless ($posts->isEmpty())
<div {{ $attributes->merge(['class' => 'text-gray-500 text-base']) }}>
    <h4 class="ml-2 text-lg font-semibold mb-2 {{ $outside_class }}">{{ $title }}</h4>
    <div class="{{  $wrapper_class }}">
        @foreach ($posts as $post)
            @if (!$loop->first && 'horizontal' !== $orientation)
                <hr />
            @endif
            @if (empty($post->image_url) && empty($post->thumbnail_image_url) )
                
                <a href="{{ route('events.show', ['post' => $post->id]) }}" class="block my-4 p-2 hover:bg-splarge-700  hover:text-white">
                    <h5 class="font-semibold">{{ $post->name }}</h5>
                    <p>
                        {{ Str::limit($post->description, 120, '...') }}
                    </p>
                    <p class="text-right">
                        {{ $post->created_at->diffForHumans() }}
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
                            <p class="text-right">
                                {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>    
                </a>
            @endif
            
        @endforeach
    </div>
    
    <p class="text-right mb-4">
        <a class="link" href="{{ route('events.index') }}">More events&hellip;</a>
    </p>
</div>

@endunless