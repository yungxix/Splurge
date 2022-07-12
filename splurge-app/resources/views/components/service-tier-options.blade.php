@props(['options'])

@php
    use Illuminate\Support\Arr;
@endphp
@unless (empty($options))
@php
    $plainItems = Arr::where($options, function ($opt, $i) {
        return !isset($opt['items']) || empty($opt['items']);
    });
@endphp
<div class="service-tier-options-container">
       @unless (empty($plainItems))
           <ul {{ $attributes->merge(['class' => 'service-tier-options']) }}>
                @foreach ($plainItems as $item)
                    <li>
                        <div>
                        {{$item['text'] ?? ''}}
                        @isset($item['html_text'])
                        {!! Purify::clean($item['html_text']) !!}
                        @endisset
                        </div>
                    </li>
                @endforeach
           </ul>
       @endunless 

       @foreach ($options as $item)
           

           @if (!isset($item['items']) || empty($item['items']))
               @continue
           @endunless
           <div class="mt-8 mb-4">
                {{ $item['text'] ?? '' }}

                @isset($item['html_text'])
                    {!! Purify::clean($item['html_text']) !!}
                @endisset
         </div>
           <ul  {{ $attributes->merge(['class' => 'service-tier-options']) }}>
                @foreach ($item['items'] as $child_item)
                    <li>
                        <div>
                        {{$child_item['text'] ?? ''}}
                        @isset($child_item['html_text'])
                        {!! Purify::clean($child_item['html_text']) !!}
                        @endisset
                        </div>
                    </li>
                @endforeach
            </ul>
       @endforeach
</div>    
@endunless
