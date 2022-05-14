@php
    $item_count = count($items);
@endphp

@props(['items'])
@unless (empty($items))
<div class="bg-gray-100">
    <div class="container mx-auto overflow-hidden">
        <div class="flex flex-row items-center h-12">
        @foreach ($items as $index => $item )
            @if ($index > 0)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            @endif

            @if ($index === $item_count - 1)
            <span class="font-bold text-base">
                {{$item['text']}}
            </span>
            @else
            <a class="link text-base" href="{{ $item['url'] }}">
                {{$item['text']}}
            </a>
            @endif
        @endforeach
        </div>
    </div>
</div>    
@endunless
