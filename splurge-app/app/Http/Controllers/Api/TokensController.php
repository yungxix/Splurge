<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class TokensController extends Controller
{
    public function create(Request $request) {
        $credentails = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        $guard = 'api';

        
        if (Auth::guard($guard)->once($credentails)) {
            $user = Auth::user();
            $token = $user->createToken('splurge_access_' . Str::random(6));

            return response()->json([
                'token' => $token->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 402);
    }
}
