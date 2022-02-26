@php
    $cancel_url = route('admin.gallery.show', ['gallery' => $gallery->id]);
    $post_url = route('admin.gallery.update', ['gallery' => $gallery->id]);
@endphp
@extends('layouts.admin')

@section('title', 'Edit Gallery')


@section('content')
    @include('partials.page-header', ['title' => 'Edit Gallery'])
    <div class="container mx-auto">
        <div class="rounded shadow border bg-gray-300 p-4">
            <p>
                Edit {{ $gallery->caption}} gallery
            </p>
        </div>
        @include('admin.screens.gallery.form', ['cancel_url' => $cancel_url,  'gallery' => $gallery,  'url' => $post_url  , 'method' => 'PATCH', 'errors' => $errors])
    </div>
    
@endsection