@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Arr;
@endphp
@props(['model'])

@unless ($model->taggables->isEmpty())
    <div class="flex flex-row items-center mt-8 px-4 py-2 gap-4 flew-wrap border-t border-gray-200">
        <span class="flex-initial pr-4">{{ Str::ucfirst(Str::plural('tag', $model->taggables->count())) }}:</span>

        @foreach ($model->taggables as $t)
        <a class="link rounded-md bg-gray-200 px-4 py-2" href="{{ route('search') }}?{{ Arr::query(['ti' => $t->tag_id, 'q' => $t->tag->name]) }}">
            {{ $t->tag->name }}
        </a>     
        @endforeach
       
    </div>    
@endunless
