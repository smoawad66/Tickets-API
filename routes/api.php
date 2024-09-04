<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('api')->prefix('v1')->group(base_path('routes/api_v1.php'));


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
