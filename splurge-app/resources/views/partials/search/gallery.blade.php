@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp
@unless ($gallery->isEmpty())
    <section class="container mx-auto">
        <h4 class="text-lg mb-4">
            Gallery
        </h4>
        @foreach ($gallery as $item)
            <div class="flex flex-row" >
                <a class="block w-1/3 rounded-md max-h-44 overflow-clip hover:ring ring-splarge-700" href="{{ route('gallery.show', $item) }}">
                    <img class="w-full" src="{{ splurge_asset($item->image_url) }}" alt="{{ $item->caption }} gallery banner" />
                </a>
                <div class="p-4">
                    <a class="link mb-4" href="{{ route('gallery.show', $item) }}">
                        <h4 class="text-lg">
                            {{ $item->caption }}
                        </h4>
                    </a>
                    {{ HtmlHelper::toParagraphs(Str::limit($item->description, 250, '...')) }}
                </div>
            </div>
        @endforeach
        <div class="flex flex-row justify-end">
            <a class="link mr-8" href="{{ route('gallery.index') }}">
                See all
            </a>
            {{ $gallery->links() }}
        </div>
    </section>
@endunless