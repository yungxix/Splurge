@php
    use App\Support\HtmlHelper;
@endphp
@include('partials.page-header', ['title' => $service->name])
<div class="container mx-auto mt-4">
    <img class="mx-auto" src="{{ splurge_asset($service->image_url) }}" />
    <div class="p-4 leading-10">
        {{ HtmlHelper::toParagraphs($service->description) }}
    </div>
    <div class="flex flex-row justify-end items-center gap-x-4 p-4 border-t border-gray-200">
        <a class="link" href="{{ route('admin.services.edit', $service) }}">
            Edit
        </a>

        <a class="link" href="{{ route('admin.services.create') }}">
            New service
        </a>
    </div>
    
</div>