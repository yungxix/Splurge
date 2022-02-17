<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index(Request $req) {
        return view('screens.about.index');
    }





    public function showContact(Request $req) {
        return view('screens.about.contact');
    }
}
