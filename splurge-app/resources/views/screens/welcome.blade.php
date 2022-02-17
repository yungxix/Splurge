@extends(config('view.defaults.layout', 'layouts.old'))

@section('content')
    @include('partials.landing.slides')

    @include('partials.landing.services')

    @include('partials.landing.pages')  

@endsection
