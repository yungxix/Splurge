@extends('layouts.admin')

@section('content')
    @include('partials.page-header', ['title' =>  __('Dashboard')])
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-2 lg:grid-cols-3">
                        @foreach ($stats['counts'] as $item )
                            <div class="md:flex flex-row rounded-md bg-slate-300 m-4  overflow-clip">
                                <div class="bg-purple-800 p-4 text-white md:w-2/5 text-center flex flex-col items-center justify-center">
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
                            </div>
                        @endforeach
                    </div>


                    <div class="grid grid-cols-2 lg:grid-cols-3">
                        @foreach ($stats['dates'] as $item )
                            @unless (is_null($item['date_value']))
                            <div class="md:flex flex-row rounded-md bg-slate-300 m-4 overflow-clip">
                                <div class="md:w-2/5 text-center bg-purple-800 p-4 text-white flex flex-col items-center justify-center">
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
                            </div>    
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

