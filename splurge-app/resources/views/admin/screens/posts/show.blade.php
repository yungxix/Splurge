@php
    use App\Support\HtmlHelper;
@endphp

@extends('layouts.admin')

@section('title', $post->name)


@section('content')
    @include('admin.screens.posts.partials.header', ['post' => $post])
    
    <div class="container mx-auto">
        <div class="bg-gray-200 rounded mx-2 mb-8 p-4">
            {{ HtmlHelper::toParagraphs($post->description) }}
        </div>
        @foreach ($post->items as $item)
            <x-post-item :post_item="$item" :admin="true"></x-post-item>
        @endforeach
        <div class="my-8">
            @include('admin.partials.tags-assignment', ['taggable' => ['id' => $post->id, 'type' => 'post']])
        </div>
    </div>
@endsection