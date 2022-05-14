@php
    use App\Support\HtmlHelper;
@endphp
<div class="md:flex flex-row">
    <div class="hidden md:block w-1/2">
        <figure class="max-h-72 overflow-clip">
            <a title="Service details" class="block" href="{{ route("admin.services.show", ['service' => $serviceTier->service_id]) }}">
                <img src="{{ splurge_asset($serviceTier->service->image_url) }}" />
            </a>
            <figcaption>
                {{ $serviceTier->service->name }} service
            </figcaption>
        </figure>
    </div>
    <div class="md:w-1/2">
        <h4 class="text-xl">
            <a title="Service details" class="link" href="{{ route("admin.services.show", ['service' => $serviceTier->service_id]) }}">
            {{$serviceTier->service->name}}
            </a>
        </h4>

        {{HtmlHelper::toParagraphs($serviceTier->service->description)}}

        <h4 class="font-bold">
            <a class="link" title="Tier details" href="{{ route("admin.service_detail.tiers.show", ['service' => $serviceTier->service_id, 'tier' => $serviceTier->id]) }}">
                {{$serviceTier->name}} tier
            </a>
        </h4>

        {{HtmlHelper::toParagraphs($serviceTier->description)}}
    </div>
</div>