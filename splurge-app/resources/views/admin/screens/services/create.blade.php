@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'All Services', 'url' => route('admin.services.index')],
        ['text' => 'New Package', 'url' => '#']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'New Packge')


@section('content')
<x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
@include('partials.page-header', ['title' => 'New Package'])
<section class="container mx-auto">
    @include('admin.screens.services.form', ['service' => $service])
</section>
@endsection