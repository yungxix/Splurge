@extends('layouts.default', ['navbar_class' => 'overbanner'])

@section('title', $title)

@section('content')
    @include('partials.landing.slides')
    
    @include('partials.page-header', ['title' => 'Search', 'sub_title' => sprintf('"%s"', $title)])

    
    <div id="search_app" class="my-8">

    </div>
    @push('scripts')
        <script>
            Splurge.search.renderGroupedTagged(document.querySelector('#search_app'), {
                tag: '{{ $tag }}',
                types: ['post', 'gallery'],
                search: ''
            });
        </script>
    @endpush
@endsection
