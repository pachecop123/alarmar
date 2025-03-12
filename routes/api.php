<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

// Rutas para usuarios
Route::apiResource('users', UserController::class);

// Rutas para dispositivos
Route::apiResource('devices', DeviceController::class);
