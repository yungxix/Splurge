@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'All Services', 'url' => route('admin.services.index')],
        ['text' => 'Edit', 'url' => '#']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Edit Services')


@section('content')
<x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
@include('partials.page-header', ['title' => 'Edit Service'])
<section class="container mx-auto">
    @include('admin.screens.services.form', ['service' => $service])
</section>
@endsection