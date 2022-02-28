@props(['model', 'image_class' => ''])
@if (preg_match('/image/i', $model->media_type))
    <figure {{ $attributes }}>
        <img class="{{ $image_class }}" src="{{  splurge_asset($model->url) }}" />
        <figcaption>{{ $model->name }}</figcaption>
    </figure>
@elseif(preg_match('/video/i', $model->media_type))
    <video {{$attributes}} src="{{ splurge_asset($image->url) }}" />
@else
    <p class="text-red-800">
        <em>Unsupported medium type: '{{ $model->media_type }}'</em>
    </p>    
@endif