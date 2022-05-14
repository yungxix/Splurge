@php
    use App\Support\HtmlHelper;
@endphp
@include('partials.page-header', ['title' => $service->name, 'sub_title' => 'Service Tiers'])
<div class="container mx-auto mt-4">
    <div class="md:flex flex-row">
        <img class="max-h-72" src="{{ splurge_asset($service->image_url) }}" />
        <div class="md:w-1/2 md:ml-8">
            <h4 class="text-gray-800 text-2xl mb-8">{{ $service->name }}</h4>
            <div class="leading-5">
                {{ HtmlHelper::toParagraphs($service->description) }}
            </div>
        </div>
    </div>
    
    <div class="flex flex-row justify-end items-center gap-x-4 p-4 mt-4 border-t border-gray-200">
        <a class="link" href="{{ route('admin.services.edit', $service) }}">
            Edit service
        </a>

        <a class="link" href="{{ route('admin.service_detail.tiers.index', $service) }}">
            Manage pricing tiers
        </a>

        <a class="link" href="{{ route('admin.services.create') }}">
            New service
        </a>
    </div>
    
    
    
</div>