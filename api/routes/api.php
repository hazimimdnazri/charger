<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\ChargerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerChargerSessionController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('verify', [AuthController::class, 'verify']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('chargers', ChargerController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('sessions', CustomerChargerSessionController::class);

    Route::group(['prefix' => 'charger'], function () {
        Route::post('/start', [CallbackController::class, 'startCharger']);
        Route::post('/stop', [CallbackController::class, 'stopCharger']);
        Route::get('/{id}/sessions', [CustomerChargerSessionController::class, 'index']);
    });
});

Route::group(['prefix' => 'callback'], function () {
    Route::post('/', [CallbackController::class, 'handleCallback']);
});
