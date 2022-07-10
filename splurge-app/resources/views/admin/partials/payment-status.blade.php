@php
    $base_class = "ml-8 px-4 py-1 rounded";
    use App\Support\HtmlHelper;
@endphp
@if ($booking->hasPayments($payments))
    @if ($booking->hasFullyPaid($payments))
    <span class="{{ $base_class }} bg-green-800 text-white">
        Fully paid for
    </span>
    @else
    <span class="{{ $base_class }} bg-splurge-800 text-black">
        Partially paid, {{HtmlHelper::renderAmount($booking->getRemainingBalance($payments))}} remaining
    </span>
    @endif
@else
    <span class="{{ $base_class }} bg-red-800 text-white">
        No payment registered yet
    </span>
@endif