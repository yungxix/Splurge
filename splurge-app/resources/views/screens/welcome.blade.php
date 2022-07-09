@extends(config('view.defaults.layout', 'layouts.old'), ['navbar_class' => 'overbanner'])

@section('title')
    Welcome
@endsection

@section('content')
    <div class="g-container-1">
    @include('partials.landing.slides')

    @include('partials.landing.services')

    </div>

    @include('partials.landing.pages')  

@endsection
