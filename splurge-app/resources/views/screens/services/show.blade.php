@php
  use App\Support\HtmlHelper;
  use Illuminate\Support\Str;
@endphp

@extends(config('view.defaults.layout', 'layouts.old'))

@section('title', $service->name)

@section('content')

<section class="container mx-auto py-6">
  <div class="pt-8 mb-4">
    <h1 class="text-3xl font-bold">{{ $service->name }}</h1>
  </div>
  <div class="md:flex flex-row">
    <div class="md:w-2/3 md:p-4">
        <img class="mx-auto" src="{{ splurge_asset($service->image_url) }}" alt="{{ $service->name }} banner" />
      <div class="my-4">
        {{ HtmlHelper::toParagraphs($service->description) }}
      </div>

      <x-tags :model="$service"></x-tags>

      @include('partials.dummy_trailer')
    </div>
    <div class="md:w-1/3 p-4 bg-gray-200 md:rounded-t-md">
      <x-widgets.other-posts title="Recent Events" :post_id="-1"></x-widgets.other-posts>
      <x-widgets.recent-gallery></x-widgets.recent-gallery>
    </div>
  </div>
  
</section>
   

@endsection