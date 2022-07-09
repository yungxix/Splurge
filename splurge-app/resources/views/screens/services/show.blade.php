@php
  use App\Support\HtmlHelper;
  use Illuminate\Support\Str;
  use App\Http\Resources\ServiceTierResource;
@endphp

@extends(config('view.defaults.layout', 'layouts.old'))

@section('title', $service->name)

@section('content')
<img class="w-full" src="{{ splurge_asset($service->image_url) }}" alt="{{ $service->name }} package banner" />
<section class="container mx-auto">
  
  <div class="mb-4 mt-4">
    <h1 class="text-3xl font-bold">{{ $service->name }}</h1>

    
  </div>
  <div class="">
    <div>
      <div class="my-4 text-2xl">
        {!! Purify::clean($service->description) !!}
      </div>

{{-- 
      @foreach ($service->items as $serviceItem)
        <div class="mt-8">
          @unless (empty($serviceItem->image_url))
            <figure>
              <img src="{{ splurge_asset($serviceItem->image_url) }}" />
              <figcaption>
                {{ $serviceItem->name }}
              </figcaption>
            </figure>
          @else
            <h4 class="text-lg text-gray-800">
              {{ $serviceItem->name }}
            </h4>
          @endunless
          <div class="mt-4">
            {{ HtmlHelper::renderParagraphs($serviceItem->description) }}
          </div>
        </div>
      @endforeach --}}


     
    </div>
    
  </div>
  
</section>


@unless ($service->tiers->isEmpty())
<section>
  <div class="container mx-auto mb-4 mt-8">
    <p class="text-2xl">
      Our Packages &hellip;
    </p>
  </div>
  @include('screens.services.partials.tiers', ['service' => $service])
</section>
@endunless


<section class="container mx-auto">
  <x-tags :model="$service"></x-tags>
</section>

<section>
  <div class="p-4 bg-gray-200 flex flex-row overflow-x-auto">
    <x-widgets.other-posts class="max-w-xs" title="Recent Events" :post_id="-1"></x-widgets.other-posts>
    <x-widgets.recent-gallery class="max-w-xs"></x-widgets.recent-gallery>
  </div>
</section>
   

@endsection
