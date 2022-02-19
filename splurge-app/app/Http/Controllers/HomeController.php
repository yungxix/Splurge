<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $req) {
        return view('screens.welcome');
    }

    public function showDashboard() {
        return view('screens.dashboard');
    }
}
