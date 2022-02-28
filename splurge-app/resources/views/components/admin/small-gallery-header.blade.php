@props(['gallery'])
<div class="flex flex-row justify-start mb-4">
    <figure class="w-1/3 max-h-40 overflow-clip rounded-l-md">
        <img src="{{ splurge_asset($gallery->image_url) }}"  />
        <figcaption>{{ $gallery->caption }}</figcaption>
    </figure>
    <div class="pl-4 pt-4">
        <h4 class="text-lg">
            {{ $gallery->caption }} <span class=" text-gray-600">Gallery</span>
        </h4>
    </div>
</div>