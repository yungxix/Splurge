@php
    use App\Support\ServiceSupport;
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
@endphp

@section('title')
    Book
@endsection
@extends(config('view.defaults.layout', 'layouts.old'))

@section('body_class', 'bg-gray-100')

@section('content')
    @include('partials.page-header', ['title' => 'Book ' . $service->name])
    <div class="">

        <section class="container mx-auto py-8">
            <div class="md:hidden">
                <div class="overflow-clip max-h-52">
                    <img alt="{{ $service->name }} service image" src="{{ splurge_asset($service->image_url) }}" />
                </div>
                <div class="mb-8 mt-4">
                {!! Purify::clean($service->description) !!}
                </div>
            </div>
           <div class="md:flex flex-row">
                <div class="md:w-1/2 p-4 bg-white">
                    @unless (empty($tier->image_url))
                        <img class="block" src="{{ splurge_asset($tier->image_url) }}" alt="{{ $tier->name }} package picture" />
                    @endunless
                    <h4 class="text-lg my-4 text-gray-600">
                        {{$tier->name}}
                    </h4>
                    <hr class="mb-8" />

                    <div id="booking_form_controls_app">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                          </svg>
                    </div>

                    @push('scripts')
                        <script>
                            Splurge.booking.renderIntegratedForm(document.getElementById("booking_form_controls_app"), {
                                renderButtons: true,
                                states: {{Js::from(config('region.states')) }},
                                errors: {{Js::from($errors->toArray())}},
                                url: '{{ route('post_booking', ['service' => $service->id]) }}',
                                serviceTierId: {{ $tier->id }},
                                companyName: '{{ config('app.name') }}',
                                galleryUrl: '{{ route('gallery.index') }}'
                            })
                        </script>
                    @endpush
                </div>
                <div class="md:w-1/2">
                    <div class="hidden md:block">
                        <div class="overflow-clip max-h-52 rounded-tr-lg">
                            <img class="w-full" alt="{{ $service->name }} service image" src="{{ splurge_asset($service->image_url) }}" />
                        </div>
                        <div class="my-4 p-2">
                            {!! Purify::clean($service->description) !!}
                        </div>

                        
                    </div>
                   

                    <div class="mt-4 md:ml-2 p-4 bg-gray-200 rounded-md">
                        @unless (empty($tier->image_url))
                        <img class="w-full" alt="{{ $tier->name }} package image" src="{{ splurge_asset($tier->image_url) }}" />
                        @else
                        <h4 class="font-bold ml-4 text-lg mb-4 text-splarge-600">
                            {{$tier->name}}
                        </h4>    
                        @endunless

                        <p class="p-4">
                            {{ $tier->description }}
                        </p>
                        
                        

                        <x-service-tier-options class="style1" :options="$tier->options"></x-service-tier-options>

                        @unless (empty($tier->footer_message))
                            <div class="mt-4 ml-4">
                                {{ HtmlHelper::toParagraphs($tier->footer_message) }}
                            </div>
                        @endunless
                    </div>
                </div>
                
           </div>
        </section>
    </div>
    
    
@endsection
