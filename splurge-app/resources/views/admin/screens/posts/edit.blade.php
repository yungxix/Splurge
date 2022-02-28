@php
    use App\Support\HtmlHelper;
@endphp
@extends('layouts.admin')

@section('title', 'Edit Post')


@section('content')
@include('partials.page-header', ['title' => 'Edit Post'])
<section class="container mx-auto">
    @include('admin.screens.posts.form', ['post' => $post])
</section>
@endsection