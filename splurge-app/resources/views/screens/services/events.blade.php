@php
  use App\Support\HtmlHelper;
  use Illuminate\Support\Str;
@endphp

@extends(config('view.defaults.layout', 'layouts.old'))

@section('title', $service->name)

@section('content')

<section class="container mx-auto py-6">
    <img class=w-full" src="{{ splurge_asset($service->image_url) }}" alt="{{ $service->name }} banner" />
    <div class="pt-8 mb-4">
    <h1 class="text-3xl font-bold">{{ $service->name }}</h1>
  </div>
  <div class="md:flex flex-row">
    <div class="md:w-2/3 md:p-4">
        @unless ($gallery->isEmpty())
        <section class="mb-8">
            <header>
                <h4 class="text-splarge-700 text-xlg">Gallery</h4>
                <div class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($gallery as $item)
                            <a href="{{ route('gallery.show', $item) }}" class="block">
                                <figure>
                                    <img src="{{ splurge_asset($item->image_url) }}" />
                                    <figcaption>
                                        {{ $item->caption }}
                                    </figcaption>
                                </figure>
                            </a>
                        @endforeach
                    </div>
                    <div class="flex flex-row justify-center">
                        {{ $gallery->links() }}
                    </div>
                </div>

            </header>
        </section>            
        @endunless


        @unless ($posts->isEmpty())
        <section>
            <header>
                <h4 class="text-splarge-700 text-xlg">Events</h4>
                <div class="mt-4">
                    <div>
                        @foreach ($posts as $item)
                        <div class="stacked">
                            <a href="{{ route('events.show', $item) }}" class="image-link">
                                <img class="w-full" alt="{{ $item->name }} event picture" src="{{ splurge_asset($item->image_url) }}" />
                            </a>
                            <div class="grow p-4">
                                <a href="{{ route('events.show', $item) }}" class="link">
                                    <h4 class="text-lg font-bold">{{ $item->name }}</h4>
                                </a>
                                {{HtmlHelper::toParagraphs(Str::limit($item->description, 250, '...'))}}
                            </div>
                        </div>        
                        @endforeach
                    </div>
                    <div class="flex flex-row justify-center">
                        {{ $posts->links() }}
                    </div>
                </div>

            </header>
        </section>            
        @endunless
      <x-tags :model="$service"></x-tags>
    </div>
    <div class="md:w-1/3 p-4 bg-gray-200 md:rounded-t-md">
      <x-widgets.other-posts title="Recent Events" :post_id="-1"></x-widgets.other-posts>
      <x-widgets.recent-gallery></x-widgets.recent-gallery>
    </div>
  </div>
  
</section>
   

@endsection
