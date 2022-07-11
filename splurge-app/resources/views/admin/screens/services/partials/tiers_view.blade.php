@php
use App\Support\HtmlHelper;
$optionLimit = isset($limit) ? $limit : -1;
@endphp
@unless($service->tiers->isEmpty())
    <div>
        <h4 class="text-gray-600 text-lg my-4">
            Packages
        </h4>
        @foreach ($service->tiers as $tier)
            <div class="mb-4 mt-4">
                <strong class="block mb-2">{{ $tier->name }}</strong>
                <div class="my-8">
                    {!! Purify::clean($tier->description) !!}
                </div>
                @if ($tier->price > 0)
                    <div class="text-lg">{{ HtmlHelper::renderAmount($tier->price) }}</div>
                @endif
                <x-service-tier-options class="style1" :options="$tier->options"></x-service-tier-options>
                <p class="text-right">
                    <a
                        href="{{ route('admin.service_detail.tiers.show', ['service' => $service->id, 'tier' => $tier->id]) }}">
                        More&hellip;
                    </a>
                </p>
            </div>
        @endforeach
    </div>
@endunless
