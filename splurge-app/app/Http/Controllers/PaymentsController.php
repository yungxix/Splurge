<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function store(PaymentRequest $request) {
        $payment = $request->acceptPayment();
        if ($request->wantsJson()) {
            return new PaymentResource($payment);
        }
        $request->session()->flash("success_message", "New payment saved");
        return redirect()->to("/");
    }
}
