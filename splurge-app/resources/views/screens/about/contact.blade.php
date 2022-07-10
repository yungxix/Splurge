@extends(config('view.defaults.layout'))

@section('title')
      Contact Us
@endsection

@section('content')

@include('partials.full-map')


<section id="contact" class="container mx-auto">
      <p class="mb-4"></p>
      @include('partials.contact')
      <div class="mb-10">
      </div>
</section>


@endsection