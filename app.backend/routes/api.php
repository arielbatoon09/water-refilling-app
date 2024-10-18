<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Water\GallonTypeController as GallonType;
use App\Http\Controllers\Water\RefillController as Refill;

// Global Access
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Authenticated
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [AuthController::class, 'users']);

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/get-gallon-type', [GallonType::class, 'getAllGallonType']);
    Route::post('/add-gallon-type', [GallonType::class, 'AddGallonType']);
    Route::post('/update-gallon-type', [GallonType::class, 'updateGallonTypes']);
    Route::post('/delete-gallon-type/{id}', [GallonType::class, 'deleteGallonType']);

    Route::get('/get-refills', [Refill::class, 'getAllRefills']);
    Route::post('/add-refills', [Refill::class, 'addRefilling']);
    Route::post('/update-refills', [Refill::class, 'updateRefilling']);
});