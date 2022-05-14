@php
    function splurge_stat_url($item) {
        if (preg_match('/gallery/i', $item['section']))  {
            return route('admin.gallery.index');
        }

        if (preg_match('/service/i', $item['section']))  {
            return route('admin.services.index');
        }

        if (preg_match('/post|event/i', $item['section']))  {
            return route('admin.posts.index');
        }
        return '#';
    }
@endphp
@extends('layouts.default')

@section('content')
    @include('partials.page-header', ['title' =>  __('Welcome')])
@endsection

