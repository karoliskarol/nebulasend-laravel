<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactSupport;
use App\Http\Controllers\Api\ContactSupportController;
use App\Http\Controllers\Api\EmailMessageController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('throttle:600,1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
        Route::post('/registration', [AuthController::class, 'registration']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::delete('/logout', [AuthController::class, 'logout']);
            Route::delete('/logoutFromAllDevices', [AuthController::class, 'logoutFromAllDevices']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('emails-messages', EmailMessageController::class);

        Route::put('/emails-messages/{emails_message}/restore', [EmailMessageController::class, 'restore']);
        Route::delete('/emails-messages/{emails_message}/softDestroy', [EmailMessageController::class, 'softDestroy']);

        Route::prefix('settings')->group(function () {
            Route::put('/updatePassword', [SettingsController::class, 'updatePassword']);
            Route::put('/updateRecipient', [SettingsController::class, 'updateRecipient']);
        });
    });

    Route::get('/statistics', StatisticsController::class);
    Route::post('/contactSupport', ContactSupportController::class);
});