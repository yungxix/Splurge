@extends(config('view.defaults.layout', 'layouts.old'), ['navbar_class' => 'overbanner'])

@section('content')
    @include('partials.landing.slides')

    @include('partials.landing.services')

    @include('partials.landing.pages')  

@endsection
