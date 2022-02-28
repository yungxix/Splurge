@extends('layouts.admin')

@section('title', 'New Gallery')


@section('content')
    @include('partials.page-header', ['title' => 'New Gallery'])
    <div class="container mx-auto">
        <div class="rounded shadow border bg-gray-300 p-4">
            <p>
                A gallery helps you gather many pictures together. Save a new gallery an add/remove pictures after.
            </p>
            <p>
                After creating a gallery you will add one or more pages for pictures to it. The pages provide a way to organize a collage view
            </p>
        </div>
        @include('admin.screens.gallery.form', ['url' => route('admin.gallery.store'), 'gallery' => $gallery, 'method' => 'POST', 'errors' => $errors])
    </div>
@endsection