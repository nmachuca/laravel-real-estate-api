<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('persona', \App\Http\Controllers\Api\PersonaController::class)->except('index');
    Route::post('/personas', [\App\Http\Controllers\Api\PersonaController::class, 'index']);
    Route::apiResource('propiedad', \App\Http\Controllers\Api\PropiedadController::class)->except('index');
    Route::post('/propiedades', [\App\Http\Controllers\Api\PropiedadController::class, 'index']);
});
