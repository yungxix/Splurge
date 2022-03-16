@php
  use App\Support\HtmlHelper;
@endphp
@extends(config('view.defaults.layout'), ['title' => 'Events'])

@section('title', $eventType == 'any' ? 'Events' : $eventType)

@section('content')
<section class="container mx-auto mb-8">
  <h2 class="text-3xl text-center mt-4 mb-4 font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Serene Events</h2>
  @foreach ($posts as $post)
    <div class="post-item mt-4 grid grid-cols-3 md:grid-cols-4">
      @unless (empty($post->image_url) && empty($post->thumbnail_image_url))
        <div class="col-span-1 overflow-hidden">
          <a class="block hover:border-2 border-splarge-800" href="{{ route('events.show', ['post' => $post->id]) }}">
          <img  src="{{ $post->thumbnail_image_url ?: $post->image_url }}" />
          </a>
        </div>  
      @endunless
      <div class="col-span-2 md:col-span-3 p-2 lg:p-4">
        <a class="hover:text-splarge-800" href="{{ route('events.show', ['post' => $post->id]) }}">
          <h4 class="font-bold">{{  $post->name }}</h4>
        </a>
        {{ HtmlHelper::toParagraphs($post->description) }}
        <p class="text-right mt-4 text-gray-700 text-sm">
          {{ $post->created_at->diffForHumans() }}
        </p>
      </div>
    </div>
  @endforeach
  <div class="flex flex-row items-center justify-end">
    {{ $posts->links() }}
  </div>
  @include('partials.dummy_trailer')
</section>







@endsection