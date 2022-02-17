@php
  use App\Support\HtmlHelper;
  use Illuminate\Support\Str;
@endphp

@extends(config('view.defaults.layout', 'layouts.old'))

@section('title', 'Our Services')

@section('content')

<section class="container mx-auto py-6">
  <div class="pt-8 mb-4">
    <h1 class="text-3xl font-bold">Our Services</h1>
  </div>
  <div class="md:flex flex-row">
    <div class="md:w-2/3 md:p-4">
      @foreach ($services->chunk(3) as $group)
        <div class="grid md:grid-cols-3 gap-2 lg:gap-4">
          @foreach ($group as $service)
            <div class="rounded-lg  mb-4  overflow-hidden shadow-md border-pink-600 border">
              <a class="block hover:bg-pink-300" href="{{ route('services.show', ['service' => $service['id']]) }}">
                <div class="overflow-clip">
                  <img class="block w-full" src="{{ asset($service['image_url']) }}" alt="{{ $service['name'] }} service picture"  />
                </div>
                <div class="px-4 py-2 bg-pink-900 text-white">
                  {{ $service['name'] }}
                </div>
                <div class="px-4 pt-2 bg-white text-gray-900">
                  {{ HtmlHelper::toParagraphs(Str::limit($service['description'], 120, '...')) }}
                </div>
              </a>
            </div>
          @endforeach 
        </div>
      @endforeach
      @include('partials.dummy_trailer')
    </div>
    <div class="md:w-1/3 p-4 bg-gray-200 md:rounded-t-md">
      <x-widgets.other-posts title="Recent Events" :post_id="-1"></x-widgets.other-posts>
      <x-widgets.recent-gallery></x-widgets.recent-gallery>
    </div>
  </div>
  
</section>
   

@endsection
