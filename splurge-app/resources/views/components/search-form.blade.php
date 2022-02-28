@props(['url', 'name' => 'q', 'placeholder' => 'Search'])

@php
    $has_error = false;
    $ring_theme = $has_error ? 'red' : 'indigo';
    $color_theme = $has_error ? 'red' : 'gray';
@endphp

<div class="mb-4 pr-4">
    <form method="GET" class="float-right" action="{{ $url }}">
        <div class="flex flex-row rounded-md overflow-hidden">
            <input type="search" placeholder="{{ $placeholder }}" class="rounded-l-md border-{{$color_theme}}-300 focus:border-{{$ring_theme}}-300 focus:ring focus:ring-{{$ring_theme}}-200 focus:ring-opacity-50" size="24" name="{{ $name }}" value="{{ app('request')->old($name, '') }}" />
            <button type="submit" class="px-4 bg-slate-500 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </form>
    <div class="clear-both"></div>
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
</div>