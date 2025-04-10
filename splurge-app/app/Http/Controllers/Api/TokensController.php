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
        
        $token = $request->user('api')->createToken('splurge_access_' . Str::random(6));

        return response()->json([
            'token' => $token->plainTextToken
        ]);
    }
}
