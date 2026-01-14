<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\WaitListController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckSchema;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::middleware([CheckSchema::class, 'auth:sanctum'])->group(function () {
        Route::resource('patient', PatientController::class)->names([
            'index' => 'patient.index',
            'store' => 'patient.store',
            'show' => 'patient.show',
            'update' => 'patient.update',
            'destroy' => 'patient.destroy',
        ]);

    Route::resources([
        'waitlist' => WaitListController::class
    ]);

    Route::resources([
        'consultation' => ConsultationController::class
    ]);

    Route::resources([
        'payment' => PaymentController::class
    ]);
});
