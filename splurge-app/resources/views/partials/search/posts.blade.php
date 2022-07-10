@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp
@unless ($posts->isEmpty())
    <section class="container mx-auto">
        <h4 class="text-lg mb-4">
            Events
        </h4>
        @foreach ($posts as $post)
            <div class="flex flex-row">
                <a class="block w-1/3 rounded-md overflow-clip max-h-44 hover:ring ring-splarge-700" href="{{ route('events.show', $post) }}">
                    <img class="w-full" src="{{ splurge_asset($post->thumbnail_image_url ?: $post->image_url) }}" alt="{{ $post->name }} event picture" />
                </a>
                <div class="p-4">
                    <a class="block link mb-4" href="{{ route('events.show', $post) }}">
                        <h4 class="text-lg mb-4">
                            {{ $post->name }}
                        </h4>
                    </a>
                    {{ HtmlHelper::toParagraphs(Str::limit($post->description, 250, '...')) }}
                </div>
            </div>
            
        @endforeach
        <div class="flex flex-row justify-end">
            <a class="link mr-8" href="{{ route('events.index') }}">
                See all
            </a>
            {{ $posts->links() }}
        </div>
    </section>
@endunless