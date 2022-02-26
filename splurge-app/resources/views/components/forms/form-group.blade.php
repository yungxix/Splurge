@props(['field' => '', 'errors'])
<div @class(['form-group', 'error' => !empty($field) && $errors->has($field) ])>
    <div class="label-wrapper">
        {{ $label ?? '' }}
    </div>
    <div class="control-wrapper">
        {{$slot }}
        @unless (empty($field))
            @error($field)
                <span class="block mb-2  text-red-600 italic">{{ $message }}</span>
            @enderror
        @endunless
    </div>
</div>