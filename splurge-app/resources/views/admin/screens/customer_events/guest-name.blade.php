<a class="link" href="{{ route('admin.customer_event_detail.guests.show', ['customer_event' => $customer_event->id, 'guest' => $model->id]) }}">
    {{ $model->name }}
    
</a>
<span class="mt-2 block font-bold">
    {{ $model->gender }}
</span>
@unless (empty($model->barcode_image_url))
<figure class="block w-12">
    <img class="w-12 h-auto" src="{{ splurge_asset($model->barcode_image_url) }}" alt="Guest, '{{ $model->name }}' barcode image" />
</figure>
@endunless


@unless ($past)
    <a class="block link rename-guest" data-target="#rename-form-{{ $model->id }}">
        Rename
    </a>
    <form action="{{ route('admin.customer_event_detail.guests.update', ['customer_event' => $customer_event->id, 'guest' => $model->id]) }}" class="hidden" id="rename-form-{{ $model->id }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="action" value="rename" />
        <input type="text" name="guest[name]" value="{{ $model->name }}" class="control block my-2" placeholder="Rename guest" />
        <button type="submit" class="btn block">
            Save
        </button>
    </form>
@endunless

