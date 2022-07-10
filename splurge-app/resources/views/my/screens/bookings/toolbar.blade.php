@php
    use App\Support\HtmlHelper;
    if (!isset($total_paid)) {
        $total_paid = $booking->payments()->sum('amount');
    }
    $balance = $booking->current_charge > 0 ? ($booking->current_charge - $total_paid) : 0;
@endphp
<div class="my-4 bg-white shadow px-4 py-5 flex flex-row items-center">
    @if ($total_paid > 0)
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="ml-4 mr-2">Total paid:</span>
        <div class="font-bold text-lg text-green-800 mr-8">
            {{HtmlHelper::renderAmount($total_paid)}}
        </div>
    @endif
    @if ($balance > 0)
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span class="mr-2">Balance to pay:</span>
        <div class="font-bold text-splarge-800 text-lg mr-8">
            {{HtmlHelper::renderAmount($balance)}}
        </div>
        
        <a class="btn" href="{{ route('my.booking_details.payments.create', ['booking' => $booking->id]) }}">
            Add a payment
        </a>
    @endif
    @if ($total_paid > 0)
        <a class="link ml-8" href="{{ route('my.booking_details.payments.index', $booking) }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>     
        </a>
    @endif
    <a class="link ml-8" href="{{ route('my.booking_details.messages.index', $booking) }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
          </svg>    
     </a>
</div>