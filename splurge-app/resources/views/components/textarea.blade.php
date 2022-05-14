@props(['disabled' => false, 'field' => null, 'errors' => null])

@php
    $has_error = !is_null($field) && !is_null($errors) && $errors->has($field);
    $ring_theme = $has_error ? 'red' : 'splurge';
    $color_theme = $has_error ? 'red' : 'splarge';
@endphp

<textarea {{ $disabled ? 'disabled' : '' }} 
{!! $attributes->merge(['class' => "rounded-md shadow-sm border-$color_theme-300 focus:border-$ring_theme-300 focus:ring focus:ring-$ring_theme-200 focus:ring-opacity-50"]) !!}>{{$slot}}</textarea>