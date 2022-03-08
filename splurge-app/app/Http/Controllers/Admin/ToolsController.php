<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ToolsController extends Controller
{
    public function executeCache(Request $request) {
        $name = $request->input('n');
        try {
            $code = Artisan::call("{$name}:cache");
            $request->session()->flash('info_message', "Called task with exit code: $code");
            
        } catch (\Throwable $th) {
            //throw $th;
            $request->session()->flash('error_message', "Task failed");
            Log::error($th);
        }
        return redirect()->route('dashboard');
    }
}
