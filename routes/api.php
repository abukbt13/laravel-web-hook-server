<?php

use App\Http\Controllers\SavingController;
use App\Http\Controllers\SendWebHookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('save',[SavingController::class,'save']);
