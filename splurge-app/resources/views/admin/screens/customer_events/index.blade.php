@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;

    
    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Customer Events', 'url' => route('admin.customer_events.index')]
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Customer Events')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Customer Events'])
    <section class="container mx-auto">
        <div class="flex flex-row justify-end items-center py-4 px-2">
            <a class="link" href="{{ route('admin.customer_events.create') }}">
                Add an event
            </a>
        </div>
        {{ $table->render() }}
        <div class="mt-12"></div>
    </section>
@endsection