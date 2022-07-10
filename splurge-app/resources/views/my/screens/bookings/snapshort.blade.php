@php
    use App\Support\HtmlHelper;
@endphp

<x-detail-container :label="'Posted on'" :vertical="true">
    {{$booking->created_at->format('l M d, Y @ h:m a')}}
    <span class="text-gray-500">
        ({{$booking->created_at->diffForHumans()}})
    </span>
</x-detail-container>
<div class="p-2 rounded bg-gray-200">
    <x-detail-container :label="'Date of event'" :vertical="true">
        {{$booking->event_date->format('l M d, Y @ h:m a')}}
        <span class="text-gray-500">
            ({{$booking->event_date->diffForHumans()}})
        </span>
    </x-detail-container>
    <x-detail-container :label="'Description'" :vertical="true">
        {{ HtmlHelper::renderParagraphs($booking->description) }}    
    </x-detail-container>
</div>
<hr class="my-4 mx-auto w-2/3 border-gray-500" />
<x-detail-container :label="'Contact'" :vertical="true">
    <div>{{$booking->customer->fullName()}}</div>
    <div>
        Email: {{$booking->customer->email}}
        <span class="ml-8">Phone: {{$booking->customer->phone}}</span>
    </div>
</x-detail-container>

<x-detail-container :label="'Location'" :vertical="true">
   <x-address-block :address="$booking->location"></x-address-block>
</x-detail-container>