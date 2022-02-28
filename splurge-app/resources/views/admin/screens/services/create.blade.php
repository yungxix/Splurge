@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'New Service')


@section('content')
@include('partials.page-header', ['title' => 'New Service'])
<section class="container mx-auto">
    @include('admin.screens.services.form', ['service' => $service])
</section>
@endsection