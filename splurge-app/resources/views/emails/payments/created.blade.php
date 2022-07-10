@php
    use App\Support\HtmlHelper;
    $date_format = "l F j, Y g:i A";
@endphp

@component('mail::message')
# New Payment

Hi, {{$payment->booking->customer->first_name}}.

We have received notification of your payment of {{HtmlHelper::renderAmount($payment->amount)}} for booking
**{{$payment->booking->code}}**.

`{{$payment->statement}}`

@component('mail::button', ['url' => $accessUrl])
Click here for details
@endcomponent

@endcomponent
