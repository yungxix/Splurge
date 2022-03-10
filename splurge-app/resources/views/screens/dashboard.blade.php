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
@extends('layouts.admin')

@section('content')
    @include('partials.page-header', ['title' =>  __('Dashboard')])
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-2 lg:grid-cols-3">
                        @foreach ($stats['counts'] as $item )
                            <a href="{{ splurge_stat_url($item) }}" class="focus:ring hover:ring ring-splarge-900 block md:flex flex-row rounded-md bg-splurge-50 m-4  overflow-clip">
                                <div class="bg-splarge-800 p-4 text-white md:w-2/5 text-center flex flex-col items-center justify-center">
                                    <h3 class="text-lg">
                                        {{ $item['section']}}
                                    </h3>
                                    <h4 class="text-4xl font-bold mt-2">
                                        {{ $item['record_count'] }}
                                    </h4>
                                </div>
                                <div class="md:w-3/5 p-4">
                                    <p class="mt-4">
                                        {{ $item['title'] }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>


                    <div class="grid grid-cols-2 lg:grid-cols-3">
                        @foreach ($stats['dates'] as $item )
                            @unless (is_null($item['date_value']))
                            <a href="{{ splurge_stat_url($item) }}"  class="block focus:ring hover:ring ring-splarge-900 md:flex flex-row rounded-md bg-splurge-50 m-4 overflow-clip">
                                <div class="md:w-2/5 text-center bg-splarge-800 p-4 text-white flex flex-col items-center justify-center">
                                    <h3 class="text-lg">
                                        {{ $item['section']}}
                                    </h3>
                                    <h4 class="font-bold mt-2">
                                        {{ $item['date_value']->format('l F d, Y') }}
                                    </h4>
                                    <h4 class="font-bold ">
                                        {{ $item['date_value']->format('h:i a') }}
                                    </h4>
                                </div>
                                <div class="md:w-3/5 p-4">
                                    <p class="mt-4">
                                        {{ $item['title'] }}
                                    </p>
                                </div>
                            </a>    
                            @endunless
                        @endforeach
                    </div>
                </div>

                @can('system')
                    <form method="POST" class="mt-8" action="{{ route('system.cache') }}">
                        @csrf
                        Prepare cache: <select name="n" required>
                            @foreach (['route', 'config', 'view'] as $v )
                                <option value="{{ $v }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <button type="submit">Send</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection

