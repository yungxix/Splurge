@php
    use App\Support\HtmlHelper;

    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'All Services', 'url' => route('admin.services.index')],
        ['text' => $service->name . ' Details', 'url' => route('admin.services.show', $service)],
        ['text' => 'Tiers', 'url' => route('admin.service_detail.tiers.index', ['service' => $service->id])],
        ['text' => 'Details', 'url' => '#']
    ];
@endphp
@extends('layouts.admin')



@section('title', sprintf("%s tier for %s", $tier->name, $service->name))


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('admin.screens.services.tiers.header', ['service' => $service])
    <hr class="mb-4 w-3/4 mx-auto" />
    
    <div class="container mx-auto">
        <div class="bg-stone-200 rounded-md m-8 shadow-md p-4">
            <h4 class="text-center text-bold text-4xl">{{ $tier->name }}</h4>   
           
            <div class="my-4">
               {{ HtmlHelper::toParagraphs($tier->description ?: '', 'text-gray-600') }}
            </div>

            @if ($tier->price)
            <h3 class="text-center text-3xl my-4">
                {{ HtmlHelper::renderAmount($tier->price) }}
               </h3>
           @endif

            @unless (empty($tier->options))
                <ol class="mt-4 ml-4 list-disc text-base">
                    @foreach ($tier->options as $option)
                        <li>
                            {{ $option['text'] }}
                        </li>
                    @endforeach
                </ol>
            @endunless
            @unless (empty($tier->footer_message))
                <div class="my-4">
                   {{ HtmlHelper::toParagraphs($tier->footer_message, 'text-gray-700') }}
                </div>
            @endunless
            <div class="flex flex-row justify-end items-center gap-8">
                <a class="link" href="{{ route('admin.service_detail.tiers.edit', ['service' => $tier->service_id, 'tier' => $tier->id]) }}">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                     </svg>
               </a>
                <x-delete-button :url="route('admin.service_detail.tiers.destroy', ['service' => $tier->service_id, 'tier' => $tier->id])"></x-delete-button>
            </div>
       </div>
    </div>
@endsection