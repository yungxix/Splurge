@php
$columns = ['', ''];
use App\Support\HtmlHelper;
use Illuminate\Support\Arr;

@endphp
@unless($service->items->isEmpty())

    <div>
        <h4 class="text-lg">Pricing</h4>
        <a class="link mb-4" href="{{ route('admin.service_detail.service_items.index', $service) }}">Manage</a>
        <x-simple-table :columns="$columns" :caption="'Pricing'">
            @foreach ($service->items->sortBy('sort_number') as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <h4 className="text-lg">
                            {{ $item->name }}
                        </h4>
                        <span className="text-gray-500 block">
                            ({{ $item->category }})
                        </span>
                    </td>
                    <td class="pb-4">
                        <div class="flex flex-col justify-end items-end">
                            @switch($item->pricing_type)
                                @case('fixed')
                                    Fixed amount:
                                    <h4 className="text-xl font-bold">
                                        {{ HtmlHelper::renderAmount($item->price) }}
                                    </h4>
                                    @if ($item->required)
                                        <span class="mt-2 px-2 py-1 uppercase rounded bg-green-800 text-white text-xs">
                                            required
                                        </span>
                                    @endif
                                @break

                                @case('incremental')
                                    <h4 className="text-xl font-bold">
                                        {{ HtmlHelper::renderAmount($item->price) }}
                                        <span class="ml-2 text-sm text-gray-700">each</span>
                                    </h4>
                                    <p>
                                        @if (Arr::get($item->options, 'minimum', 0) == 0)
                                            Up to
                                            {{ HtmlHelper::renderAmount(Arr::get($item->options, 'maximum') * $item->price) }}
                                        @else
                                            From
                                            {{ HtmlHelper::renderAmount(Arr::get($item->options, 'minimum') * $item->price) }}
                                            to {{ HtmlHelper::renderAmount(Arr::get($item->options, 'maximum') * $item->price) }}
                                        @endif
                                    </p>
                                @break

                                @case('percentage')
                                    <h4 className="text-xl">
                                        <span class="font-bold">
                                            {{ Arr::get($item->options, 'rate') }}%
                                        </span>
                                        
                                    </h4>
                                    <p class="text-sm text-gray-700">on
                                        {{ Arr::get($item->options, 'base', 'default') }} items</p>
                                @break

                                @default
                            @endswitch

                            
                        </div>

                    </td>
                </tr>
            @endforeach
        </x-simple-table>
    </div>
@endunless
