@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp
@unless ($posts->isEmpty())
    <section class="container mx-auto">
        <h4 class="text-lg">
            Events
        </h4>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($posts as $post)
                <a class="block rounded-md overflow-clip hover:ring ring-splarge-700" href="{{ route('events.show', $post) }}">
                    <figure class="w-full">
                        <img class="w-full" src="{{ splurge_asset($post->thumbnail_image_url ?: $post->image_url) }}" alt="{{ $post->name }} event picture" />
                        <figcaption class="px-4">
                            {{ $post->name }}
                        </figcaption>
                    </figure>
                    <div class="p-4">
                        {{ HtmlHelper::toParagraphs(Str::limit($post->description, 150)) }}
                    </div>
                </a>
            @endforeach
        </div>
        <div class="flex flex-row justify-end">
            <a class="link mr-8" href="{{ route('events.index') }}">
                See all
            </a>
            {{ $posts->links() }}
        </div>
    </section>
@endunless