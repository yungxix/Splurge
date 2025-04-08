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
            $code = '';
            if (empty($name)) {
                $codes = array_map(function ($item) {
                    return
                    Artisan::call("{$item}:cache");
                }, ['view', 'config', 'routes']);

                $code = implode(', ', $codes);
            } else {
                $code = Artisan::call("{$name}:cache");
            }
            
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Done  with codes ' . $code]);
            } else {
                $request->session()->flash('info_message', "Called task with exit code: $code");
                return redirect()->route('admin.admin_dashboard');
                
            }
            
            
        } catch (\Throwable $th) {
            Log::error($th);
            
            
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Error']);
            } else {
                $request->session()->flash('error_message', "Task failed");
                return redirect()->route('admin.admin_dashboard');
            }
            
        }
    }
}
