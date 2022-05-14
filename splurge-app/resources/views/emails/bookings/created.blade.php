@php
    use App\Support\HtmlHelper;
    $date_format = "l F j, Y g:i A";
@endphp
@component('mail::message')
# New Booking ( {{$booking->code}} )

Hi, {{$booking->customer->first_name}}.

Thank you for booking on {{config('app.name')}}

@component("mail::panel")
## {{$booking->serviceTier->service->name}} Service

### {{$booking->serviceTier->name}} Plan

Booking verification code is **{{$booking->code}}**

@foreach (explode("\n", $booking->serviceTier->description) as $line)
{{$line}}

@endforeach

@if ($booking->serviceTier->price > 0)
Priced at **{{HtmlHelper::renderAmount($booking->serviceTier->price)}}**
@else
Pricing is subject to negotiation. 
@endif


@unless (empty($booking->serviceTier->options))
### What you get from {{$booking->serviceTier->name}} tier

@foreach ($booking->serviceTier->options as $option)
🚀 {{$option['text']}}

@endforeach
@endunless

@unless (empty($booking->serviceTier->footer_message))
@foreach (explode("\n", $booking->serviceTier->footer_message) as $line)
{{$line}}<br/>
@endforeach    
@endunless




@endcomponent


@component("mail::panel")

### Details of the event

- Date: {{$booking->event_date->format($date_format)}}

- Contact name: {{$booking->customer->first_name}} {{$booking->customer->last_name}}

- Contact email & phone: {{$booking->customer->email}}  {{$booking->customer->phone}}

- Location
    - {{$booking->location->line1}}<br/>
    @unless (empty($booking->location->line2))
    - {{$booking->location->line2}}
    @endunless
    ,{{$booking->location->state}} {{$booking->location->zip}}
@endcomponent

@unless ($booking->payments->isEmpty())
### Payments
@component("mail::table")
| Reference | Amount | Date |
|-----------|--------:|------|
@foreach ($booking->payments as $payment)
| {{$payment->statement}} | {{HtmlHelper::renderAmount($payment->amount)}} | {{$payment->created_at->format($date_format)}}|   
@endforeach

@endcomponent
@endunless



@component('mail::button', ['url' => $accessUrl])
Access this booking here
@endcomponent
@endcomponent
