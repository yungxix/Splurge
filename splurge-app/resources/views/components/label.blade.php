@props(['value', 'field' => null, 'errors' => null])

@php
    $color_theme = is_null($field) || is_null($errors) || !$errors->has($field) ? 'gray' : 'red';
@endphp
<label 
@if (isset($field))
for="{{ $field }}"
@endif
{{ $attributes->merge(['class' => "block font-medium text-sm text-{$color_theme}-700"]) }}>
    {{ $value ?? $slot }}
</label>
