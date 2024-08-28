<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SendWebHookController extends Controller
{
    public function sendWebHook(Request $request){

        Log::info('Incoming request:', $request->all());
    }
}
