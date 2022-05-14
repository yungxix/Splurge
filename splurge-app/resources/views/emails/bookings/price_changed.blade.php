@php
    use App\Support\HtmlHelper;
    $date_format = "l F j, Y g:i A";
@endphp
@component('mail::message')
# Price Set  (Booking \#{{$booking->code}} )

Hi, {{$booking->customer->first_name}}.

A price has been set on your booking

@component("mail::panel")
@component("mail::table")
| **Amount** | {{HtmlHelper::renderAmount($booking->current_charge)}}
@endcomponent

@endcomponent

*{{$footer_message ?? ''}}*

@component('mail::button', ['url' => $accessUrl])
Access this booking here
@endcomponent
@endcomponent
