@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'All Services', 'url' => route('admin.services.index')],
        ['text' => 'New Service', 'url' => '#']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'New Service')


@section('content')
<x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
@include('partials.page-header', ['title' => 'New Service'])
<section class="container mx-auto">
    @include('admin.screens.services.form', ['service' => $service])
</section>
@endsection