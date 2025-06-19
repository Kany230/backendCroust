<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// -------------------- Auth routes
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

//A proteger plus tard
Route::post('/complete-registration', [\App\Http\Controllers\AuthController::class, 'completeRegistration']);
Route::get('/profile', [\App\Http\Controllers\AuthController::class, 'profile']);


// --------------------Local routes
Route::get('/locals', [\App\Http\Controllers\LocalController::class, 'index']);
Route::post('/locals', [\App\Http\Controllers\LocalController::class, 'store']);
Route::get('/locals/{id}', [\App\Http\Controllers\LocalController::class, 'show']);
Route::put('/locals/{id}', [\App\Http\Controllers\LocalController::class, 'update']);
Route::delete('/locals/{id}', [\App\Http\Controllers\LocalController::class, 'destroy']);

// -------------------- Reservation routes