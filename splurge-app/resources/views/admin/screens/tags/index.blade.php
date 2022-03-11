@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'Tags')


@section('content')
    @include('partials.page-header', ['title' => 'Tags'])
    <section class="container mx-auto">
        <div id="tags_app" class="mb-8"></div>
    </section>

    @push('scripts')
        <script   nonce="{{ csp_nonce() }}">
            Splurge.admin.tags.renderTableView(document.querySelector('#tags_app'), {!! Js::from($tags) !!}, {
                baseURL: '{{ route('admin.tags.index') }}'
            });
        </script>
    @endpush
@endsection