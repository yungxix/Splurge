@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp
@unless ($gallery->isEmpty())
    <section class="container mx-auto">
        <h4 class="text-lg">
            Gallery
        </h4>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($gallery as $item)
                <a class="block ounded-md overflow-clip hover:ring ring-splarge-700" href="{{ route('gallery.show', $item) }}">
                    <figure class="w-full">
                        <img class="w-full" src="{{ splurge_asset($item->image_url) }}" alt="{{ $item->caption }} gallery banner" />
                        <figcaption>
                            {{ $item->caption }}
                        </figcaption>
                    </figure>
                    <div class="p-4">
                        {{ HtmlHelper::toParagraphs(Str::limit($item->description, 150)) }}
                    </div>
                </a>
            @endforeach
        </div>
        <div class="flex flex-row justify-end">
            <a class="link mr-8" href="{{ route('gallery.index') }}">
                See all
            </a>
            {{ $gallery->links() }}
        </div>
    </section>
@endunless