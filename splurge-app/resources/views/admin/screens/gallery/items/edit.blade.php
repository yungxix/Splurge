@extends('layouts.admin')

@section('title', 'Edit Gallery Page')


@section('content')
    @include('partials.page-header', ['title' => 'Edit Gallery Page'])
    <div class="container mx-auto">
        <x-admin.small-gallery-header :gallery="$gallery"></x-admin.small-gallery-header>
        @include('admin.screens.gallery.items.form', ['gallery' => $gallery, 'gallery_item' => $gallery_item])
    </div>
    
@endsection