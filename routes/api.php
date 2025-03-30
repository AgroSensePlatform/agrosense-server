<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\MeasurementController;



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

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user', [AuthController::class, 'update']); // Update the authenticated user
    Route::delete('/user', [AuthController::class, 'delete']); // Delete the authenticated user
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/farms', [FarmController::class, 'index']);
    Route::post('/farms', [FarmController::class, 'store']);
    Route::get('/farms/{farm}', [FarmController::class, 'show']);
    Route::put('/farms/{farm}', [FarmController::class, 'update']);
    Route::delete('/farms/{farm}', [FarmController::class, 'destroy']);
});



Route::middleware('auth:sanctum')->group(function () {
    // Sensor routes
    Route::get('/sensors', [SensorController::class, 'index']); // List all sensors for the authenticated user
    Route::post('/sensors', [SensorController::class, 'store']); // Add a new sensor
    Route::put('/sensors/{sensor}', [SensorController::class, 'update']); // Update a specific sensor
    Route::get('/sensors/{sensor}', [SensorController::class, 'show']); // Show a specific sensor
    Route::delete('/sensors/{sensor}', [SensorController::class, 'destroy']); // Delete a specific sensor
    Route::post('/sensors/scan', [SensorController::class, 'scan']); // Decide to create or update a sensor
});

Route::post('/measurements', [MeasurementController::class, 'store']);
