@props(['disabled' => false, 'field' => null, 'errors' => null])

@php
    $has_error = !is_null($field) && !is_null($errors) && $errors->has($field);
    $ring_theme = $has_error ? 'red' : 'indigo';
    $color_theme = $has_error ? 'red' : 'gray';
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => "rounded-md shadow-sm border-$color_theme-300 focus:border-$ring_theme-300 focus:ring focus:ring-$ring_theme-200 focus:ring-opacity-50"]) !!}>
