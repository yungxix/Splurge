@php
  use App\Support\HtmlHelper;
  use Illuminate\Support\Str;
@endphp

@extends(config('view.defaults.layout', 'layouts.old'))

@section('title', 'Our Packages')

@section('content')

<section class="container mx-auto py-6">

  @if ($services->isEmpty())
  <div class="bg-white">
    <h4 class="text-lg font-bold text-splarge-800 text-center my-8">
        Sorry. This page is under construction
    </h4>
    <img class="mx-auto w-3/5" src="{{ asset('images/serene_empty_collage.png') }}" alt="Empty gallery image" />
</div>
  @else
  <div class="pt-8 mb-4">
    <h1 class="text-3xl font-bold">Our Packages</h1>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-3">
    @foreach ($services as $service)
      <a href="{{ route('services.show', $service) }}" class="hover:ring ring-splarge-600 block m-2 md:m-4 rounded-md shadow">
        <div class="max-h-72 overflow-clip rounded-t-md">
            <img alt="{{ $service->name }} service image" class="w-full" src="{{ splurge_asset($service->image_url) }}"/>
        </div>
        <div class="bg-white p-4">
          <h4 class="text-gray-900 mb-4 font-bold">{{ $service->name }}</h4>
          {{ HtmlHelper::renderParagraphs(Str::limit($service->description, 120, '...')) }}
        </div>
      </a>
    @endforeach
  </div>  
  @endif
  
  <div class="mb-8"></div>
</section>
   

@endsection
