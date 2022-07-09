@php
use App\Support\HtmlHelper;
@endphp
<div class="py-4 md:pb-4 g-container-1">
    <div class="container mx-auto">
        @foreach ($service->tiers as $tier)
        <div class="service-tier-wrapper">
            <a href="{{ route('book_service', ['service' => $service->id]) }}?tier={{ $tier->id }}" class="bookmark"></a>
            <div class="flex flex-row">
                <div class="flex-grow">
                    <div class="service-tier-container">
                        
                        <h4 class="font-bold uppercase text-lg text-splurge-200">
                            {{ $tier->name }}
                        </h4>

                        <div class="my-8">
                            {!! Purify::clean($tier->description) !!}
                        </div>

                        @unless(empty($tier->options))
                            <x-service-tier-options class="style2" :options="$tier->options"></x-service-tier-options>
                        @endunless

                        <div class="mt-2">
                            {{ HtmlHelper::renderParagraphs($tier->footer_message) }}
                        </div>

                        <p class="my-4 text-right">
                            <a class="btn yellow"
                                href="{{ route('book_service', ['service' => $service->id]) }}?tier={{ $tier->id }}">
                                Book {{ $tier->name }}
                            </a>
                        </p>


                    </div>


                </div>
                @unless(is_null($tier->image_url))
                    <div class="w-1/3">
                        <img class="block" alt="{{ $tier->name }} tier image"
                            src="{{ splurge_asset($tier->image_url) }}" />
                    </div>
                @endunless
            </div>

        </div>
    @endforeach
    </div>
  

</div>
