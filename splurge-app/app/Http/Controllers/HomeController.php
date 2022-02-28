<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('screens.welcome');
    }

    public function showDashboard() {
        return view('screens.dashboard');
    }

    public function getSearch(Request $request) {
        $title = $request->input('q');
        $tag = $request->input('ti');
        return view('screens.search', ['title' => $title, 'tag' => $tag]);
    }


    public function getTaggedSearch(Request $request) {
        $type = $request->input('type');
        $provider = app("{$type}_search_provider");
        return $provider->search($request);
    }
}
