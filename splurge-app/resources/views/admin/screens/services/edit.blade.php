@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'Edit Service')


@section('content')
@include('partials.page-header', ['title' => 'Edit Service'])
<section class="container mx-auto">
    @include('admin.screens.services.form', ['service' => $service])
</section>
@endsection