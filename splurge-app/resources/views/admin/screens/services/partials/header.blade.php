@php
    use App\Support\HtmlHelper;
    use Illimunate\Support\Str;
@endphp
@include('partials.page-header', ['title' => $service->name])
<div class="container mx-auto mt-4">
    <img class="mx-auto" src="{{ splurge_asset($service->image_url) }}" />
    <div class="flex flex-row justify-end items-center gap-x-4 p-4 mt-4 border-t border-gray-200">
        <span class="mr-8 bg-slate-700 text-white rounded py-2 px-4">
            {{config('view.services.displays.' . $service->display)}}
        </span>
        <a class="link" href="{{ route('admin.services.edit', $service) }}">
            Edit
        </a>

        <a class="link" href="{{ route('admin.service_detail.tiers.index', $service) }}">
            Manage pricing tiers
        </a>

        <a class="link" href="{{ route('admin.services.create') }}">
            New service
        </a>
    </div>

    @unless (isset($hide_description))
        <div class="p-4 leading-10">
            {{ HtmlHelper::toParagraphs($service->description) }}
        </div>
            
    @endunless
    
</div>