@php
    use App\Support\ServiceSupport;
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp

@extends(config('view.defaults.layout', 'layouts.default'))

@section('title')
    Book
@endsection

@section('body_class', 'bg-gray-100')

@section('content')
    @include('partials.page-header', ['title' => 'Select a Package'])
    <div class="">
        @if ($services->isEmpty())
            <x-empty-view :message="'Apologies, this page is not ready yet'"></x-empty-view>
        @else
        <section class="container mx-auto py-8">
            <div class="mb-8">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($services as $service)
                        <div class="flex flex-col items-stretch drop-shadow-md bg-white overflow-clip  rounded-md">
                            <div class="h-56 md:h-56 mb-4 overflow-clip">
                                <img class="w-full" src="{{ $service->image_url }}" />
                            </div>
                            <div class="p-2 grow">
                                
                                <h4 class="text-xl font-bold mb-4">
                                    {{ $service->name }}
                                </h4>
                                {{ HtmlHelper::toParagraphs(Str::limit($service->description, 150, '...'), 'text-gray-500') }}
                            </div>

                            <a href="{{ route('book_service', ['service' => $service->id]) }}"
                                    class="block my-8 mx-4 bg-slate-700 hover:bg-slate-900 active:bg-splarge-900 text-white px-4 py-2 text-center">
                                BOOK
                            </a>
                                
                        </div>
                    @endforeach
                </div>
            </div>
            
        </section>
        @endif
        
    </div>
    
    
@endsection
