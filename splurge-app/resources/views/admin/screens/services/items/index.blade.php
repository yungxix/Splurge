@php
    use App\Support\HtmlHelper;
    use App\Http\Resources\ServiceItemResource;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Service Items')


@section('content')
    @include('partials.page-header', ['title' => 'Service Pricing'])
    <section class="container mx-auto">
        <div class="md:flex flex-row">
            <div class="md:w-2/3 p-4">
                <div id="pricing_app"></div>
            </div>
            <div class="md:w-1/3 pb-8">
                <a class="rounded-md block mb-4 overflow-hidden" href="{{ route('admin.services.show', $service) }}">
                    <img src="{{ splurge_asset($service->image_url) }}" alt="{{ $service->name }} service picture" />
                </a>
                {{ HtmlHelper::toParagraphs(Str::limit($service->description, 250, '...')) }}
                <a class="link"  href="{{ route('admin.services.show', $service) }}">
                    More...
                </a>
            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            Splurge.admin.pricing.renderTableView(
                document.getElementById('pricing_app'),{
                    baseURL: '{{ route('admin.service_detail.service_items.index', $service) }}',
                    items: {{ Js::from(ServiceItemResource::collection($service->items->sortBy('sort_number'))->resolve(app('request'))) }}
                }
            );
        </script>
    @endpush
@endsection