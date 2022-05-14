@php
    use App\Support\ServiceSupport;
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;
    use Illuminate\Support\Arr;

    $states = array_map(function ($value) {
        return ['name' => $value];
    }, config('region.states'));
@endphp

@extends(config('view.defaults.layout', 'layouts.old'))

@section('body_class', 'bg-gray-100')


@section('content')
    @include('partials.page-header', ['title' => 'Book ' . $service->name])
    <div class="">

        <section class="container mx-auto py-8">
            <div class="flex flex-row">
                <div class="rounded-md overflow-clip max-h-80">
                    <img  alt="{{ $service->name }} service image" src="{{ splurge_asset($service->image_url) }}" />
                </div>
                <div class="pl-8 pt-4">
                    {{ HtmlHelper::toParagraphs(Str::limit($service->description, 250, '...')) }}
                </div>
            </div>

            <div class="mt-8" id="booking_form_container">
                <div class="flex flex-col items-center justify-center px-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                </div>

            </div>
            
            
        </section>
    </div>
    
    
@endsection

@push('scripts')
    <script>
        Splurge.booking.renderForm(document.querySelector("#booking_form_container"), {
            tiers: {{Js::from($service->tiers)}},
            contact: {
                email: "demo@splurge.ng",
                phone: "08120000000demo",
                addresses: []
            },
            catalogUrl: "{{ route('gallery.index') }}",
            postUrl: "{{ route('post_booking', $service) }}",
            states: {{Js::from($states)}},
            serviceId: {{$service->id}},
            paymentUrl: '{{ route('payments.store') }}'
        })
    </script>                        
@endpush

