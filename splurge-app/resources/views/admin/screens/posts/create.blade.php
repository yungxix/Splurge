@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'New Post')


@section('content')
@include('partials.page-header', ['title' => 'New Post'])
<section class="container mx-auto">
    @include('admin.screens.posts.form', ['post' => $post])
</section>
@endsection