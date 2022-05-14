<?php

namespace App\Http\Controllers\My;

use App\Events\PaymentCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\SplurgeAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    public function index(Request $request, Booking $booking) {
        $total_paid = $booking->payments->sum("amount");
        return view('my.screens.bookings.payments.index', ['booking' => $booking, 'total_paid' => $total_paid]);
    }

    public function create(Request $request, Booking $booking) {
        $required_amount = $booking->current_charge;
        if ($required_amount < 1) {
            $request->session()->flash('error_message', 'Cannot make a payment at this time');
            return redirect()->back();
        }
        $total_paid = $booking->payments->sum("amount");
        
        $balance = $required_amount - $total_paid;

        if ($balance < 1) {
            $request->session()->flash('error_message', 'Nothing to pay for');
            return redirect()->back();
        }

        $access_token = SplurgeAccessToken::create([
            'token' => Str::random(24) . '_pmt',
            'access_id' => $booking->id,
            'access_type' => Booking::class,
            'user_id' => 0,
            'expires_at' => Carbon::now()->addMinutes(20)
        ]);
        
        $redirect_url = route('my.booking_details.payments.accept', ['booking' => $booking->id]) . '?' . Arr::query([
            'spt' => $access_token->token,
            'amount' => $balance
        ]);

        $booking->loadMissing(['customer', 'location']);

        // TODO: In the future this is supposed to go to paystack. For now just simulate it

        return view('my.screens.bookings.payments.create',
         [
             'booking' => $booking,
             'balance' => $balance,
             'total_paid' => $total_paid,
             'redirect_url' => $redirect_url,
             'token' => $access_token->token
        ]);
    }

    public function acceptPayment(Request $request, Booking $booking) {
        // Not sure how to determine successful payment for now
        if ("success" !== $request->input('status')) {
            $request->session()->flash('error_important_message', 'Payment failed');
            return redirect()->route('my.bookings.show', $booking);
        }
        return DB::transaction(function () use ($request, $booking) {
            $token = $request->input('spt');
            $access_token = SplurgeAccessToken::where([
                'token' => $token,
                'access_id' => $booking->id,
                'access_type' => Booking::class
            ])->firstOrFail();
            try {
                $payment = new Payment();
                
                $payment->amount = $request->input('amount');
                
                $payment->code = $payment->generateCode();
                
                $payment->statement = sprintf('REF: %s', $request->input('reference'));
                
                $booking->payments()->save($payment);
                
                $request->session()->flash('success_important_message', 'Payment recorded');

                event(new PaymentCreatedEvent($payment));

                return redirect()->route('my.bookings.show', $booking);
            } finally  {
                //throw $th;
                $access_token->delete();
            }

            

        });
        


    }


}
