@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'Service Details')


@section('content')
    @include('admin.screens.services.partials.header', ['service' => $service])
    <hr class="mb-4 w-3/4 mx-auto" />
    <div class="container mx-auto">
        <div class="my-8">
            @include('admin.partials.tags-assignment', ['taggable' => ['id' => $service->id, 'type' => 'service']])
        </div>
    </div>
@endsection