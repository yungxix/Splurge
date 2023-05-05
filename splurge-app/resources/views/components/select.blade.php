@props(['value' => null, 'options', 'name', 'text_as_value' => false, 'required' => false])
<select name="{{ $name }}"  {!! $attributes->merge(['class' => 'control']) !!} @required($required)>
    @foreach ($options as $key => $text )
        @php
            $current_value = $text_as_value ? $text : $key;
        @endphp
        <option @selected($value == $current_value) value="{{ $current_value }}">
            {{ $text }}
        </option>
    @endforeach
</select>