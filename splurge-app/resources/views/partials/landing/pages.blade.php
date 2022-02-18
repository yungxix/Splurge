@php
use App\Support\HtmlHelper;
use Illuminate\Support\Str;

@endphp


<section id="pages">
    <div class="container mx-auto pt-5">
        <div class="heado">
            <h4 class="text-xl lg:text-2xl text-white">SPLURGE EVENTS LUXURY WEDDING PLANNERS, PARTY PLANNERS AND EVENT DESIGNER</h4>
        </div>
        
        @foreach ($posts->chunk(3) as  $group)
        <div class="page-item mt-2 grid grid-cols-1 gap-4 lg:grid-cols-3 md:grid-cols-2">
            @foreach ($group as $post)
                <div class="p-2">
                    <a href="{{ route('events.show', ['post' => $post->id]) }}">
                        <h4 class="text-lg md:text-2xl">{{ $post->name }}</h4>
                    </a>
                    {{ HtmlHelper::toParagraphs(Str::limit($post->description, 350, '...')) }}
                    <p>
                        <a href="{{ route('events.show', ['post' => $post->id]) }}">
                            More&hellip;
                        </a>
                    </p>
                </div>      
            @endforeach
        </div>    
        @endforeach
    </div>
    
</section>
