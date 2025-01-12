<?php

use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resources([
        'patient' => PatientController::class
    ]);

    Route::resources([
        'waitlist' => \App\Http\Controllers\WaitListController::class
    ]);

    Route::resources([
        'consultation' => \App\Http\Controllers\ConsultationController::class
    ]);
});
