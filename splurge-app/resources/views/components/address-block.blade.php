@props(['address'])
<address>
    @unless (empty($address->name))
        <strong class="block">{{ $address->name }}</strong>
    @endunless
    <em class="block">{{ $address->line1 }}</em>
    @unless (empty($address->line2))
    <em class="block">{{ $address->line2 }}</em>
    @endunless
    <em class="block">
        {{$address->state}}
        @unless ( empty($address->zip) )
        &mdash; {{$address->zip}}
        @endunless
    </em>
</address>
