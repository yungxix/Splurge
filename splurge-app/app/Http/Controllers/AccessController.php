<?php

namespace App\Http\Controllers;

use App\Support\OneTimeAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{

    public function index() {
        return redirect()->to('/');
    }

    public function create(OneTimeAccessService $service) {
       $response = $service->start();
       return redirect()->to($response['url']);
    }

    public function destroy(Request $request) {
        Auth::guard('my')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


}
