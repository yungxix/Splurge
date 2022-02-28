@php
    use App\Support\HtmlHelper;
@endphp

@extends('layouts.admin')

@section('title', 'Edit Section')


@section('content')
    @include('partials.page-header', ['title' => 'Edit Section', 'sub_title' => "\"{$post->name}\""])
    
    <div class="container mx-auto">
        @include('admin.screens.posts.items.form', ['post_item' => $post_item])
    </div>
@endsection