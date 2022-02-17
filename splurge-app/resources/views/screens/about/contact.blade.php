@extends(config('view.defaults.layout'))


@section('content')

@include('partials.full-map')


<section id="contact" class="container">
      <p class="mb-4"></p>
      @include('partials.contact')
      <div class="mb-10">
      </div>
      @include('partials.dummy_trailer')
</section>


@endsection