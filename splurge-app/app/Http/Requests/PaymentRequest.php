<?php

namespace App\Http\Requests;

use App\Events\PaymentCreatedEvent;
use App\Models\Payment;
use App\Models\SplurgeAccessToken;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class PaymentRequest extends FormRequest
{
    private $accessToken;
    private $loaded;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->hasVerificationCode();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            "reference" => "required",
        ];
    }

    public function acceptPayment(): Payment {
        $payment = $this->acceptPaymentImpl();
        
        event(new PaymentCreatedEvent($payment));

        return $payment;
        

    }

    private function acceptPaymentImpl(): Payment {
        return DB::transaction(function () {
            $booking = $this->accessToken->access;
            $payment = new Payment();
            $sanitized = $this->safe();
            $payment->amount = $sanitized->amount;
            $payment->statement = sprintf("Ref: %s", $sanitized->reference);
            $payment->code = $payment->generateCode();
            $booking->payments()->save($payment);
            if (!config("app.debug")) {
                $this->accessToken->delete();
            }
            return $payment;
        });
    }

    private function hasVerificationCode(): bool {
        $this->loadVerificationToken();
        return !is_null($this->accessToken) && !$this->accessToken->isExpired();
    }

    private function loadVerificationToken() {
        // if ($this->loaded === true) {
        //     return;
        // }
        // $this->loaded = true;
        // $this->accessToken = SplurgeAccessToken::where([
        //     "token" => $this->input("verification_code"),
        //     'access_type' => Booking::class
        // ])
        // ->first();
        // return $this->accessToken;
    }
}
