@php
use App\Support\HtmlHelper;
$optionLimit = isset($limit) ? $limit : -1;
@endphp
@unless($service->tiers->isEmpty())
    <div>
        <h4 class="text-gray-600 text-lg my-4">
            Tiers
        </h4>
        @foreach ($service->tiers as $tier)
            <div class="mb-4 mt-4">
                <strong class="block mb-2">{{ $tier->name }}</strong>
                @if ($tier->price > 0)
                    <div class="text-lg">{{ HtmlHelper::renderAmount($tier->price) }}</div>
                @else
                    <p>
                        <em>Negotiable</em>
                    </p>
                @endif
                <ol class="ml-4 mt-4 list-disc">
                    @if ($optionLimit > 0)
                        @foreach (array_slice($tier->options, 0, $optionLimit) as $option)
                            <li class="pb-2">
                                {{ $option['text'] }}
                            </li>
                        @endforeach
                        @if (count($tier->options) > $optionLimit)
                            <li>
                                {{(count($tier->options) - $optionLimit)}} more &hellip;
                            </li>
                        @endif
                    @else
                        @foreach ($tier->options as $option)
                            <li class="pb-4">
                                {{ $option['text'] }}
                            </li>
                        @endforeach
                    @endif
                </ol>
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
