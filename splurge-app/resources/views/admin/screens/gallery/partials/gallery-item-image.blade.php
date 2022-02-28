@php
    use Illuminate\Support\Str;
@endphp
<div>
    <a class="block" href="{{ route('admin.gallery.show', ['gallery' => $model->id]) }}">
        <div  class="flex flex-col justify-start">
            <div class="grow bg-pink-300">
                <h4 class="text-lg">{{ $model->caption }}</h4>
                <figure class="block mx-auto w-36 overflow-clip rounded md:rounded-md">
                    <img class="block" src="{{ asset($model->image_url) }}" alt="{{ $model->caption }} gallery image"/>
                </figure>
            </div>
            <div class="px-6 py-4 text-pink-900 flex flex-row items-center justify-start">
                {{ $model->items_count }} {{ Str::plural('page', $model->items_count) }}
                <span class="ml-8">
                    {{ $model->media_items_count }} {{ Str::plural('picture', $model->media_items_count) }}
                </span>
            </div>
        </div>
    </a>
</div>