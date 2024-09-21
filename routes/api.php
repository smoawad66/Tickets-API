<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\ApiGuestMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('api')->prefix('v1')->group(base_path('routes/api_v1.php'));


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', fn (Request $request) => $request->user());
});


Route::middleware(ApiGuestMiddleware::class)->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});
