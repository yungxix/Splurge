@php
    use App\Support\HtmlHelper;
@endphp
<div>
    <div>
        <img src="{{ splurge_asset($service->image_url) }}" alt="{{ $service->name }} image" class="w-full" />
    </div>
    @include('admin.screens.services.partials.links')
    <div>
        {!! Purify::clean($service->description) !!}
    </div>
    @include('admin.screens.services.partials.tiers_view', ['service' => $service, 'limit' => 2])
</div>