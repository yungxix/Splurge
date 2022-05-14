@php
    use App\Support\HtmlHelper;
@endphp
<div class="flex flex-row">
    <div class="w-1/2">
        <div class="overflow-clip max-h-56">
            <img src="{{ splurge_asset($service->image_url) }}" alt="{{ $service->name }} image" class="w-full" />
        </div>
        @include('admin.screens.services.partials.links')
        <div>
            {{ HtmlHelper::renderParagraphs($service->description) }}
        </div>
    </div>
    <div class="w-1/2 pl-8">
        @include('admin.screens.services.partials.tiers_view', ['service' => $service, 'limit' => -1])
    </div>
</div>