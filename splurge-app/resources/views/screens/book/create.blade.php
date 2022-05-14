@php
    use App\Support\ServiceSupport;
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp

@extends(config('view.defaults.layout', 'layouts.old'))

@section('body_class', 'bg-gray-100')

@section('content')
    @include('partials.page-header', ['title' => 'Book ' . $service->name])
    <div class="">

        <section class="container mx-auto py-8">
           <div class="flex flex-row justify-start items-start">
                <div class="w-1/2 md:w-1/3">
                    <h4 class="text-lg text-gray-600">
                        Booking Form
                    </h4>
                    <p>
                        Tell us about your event below
                    </p>
                </div>
                <div class="w-1/2 md:w-2/3">
                    <div class="overflow-clip max-h-52">
                        <img alt="{{ $service->name }} service image" src="{{ splurge_asset($service->image_url) }}" />
                    </div>
                    {{ HtmlHelper::toParagraphs($service->description) }}
                </div>
                
           </div>
        </section>
    </div>
    
    
@endsection
