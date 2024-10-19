<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\GallonTypeController as GallonType;
use App\Http\Controllers\Admin\RefillController as AdminRefill;
use App\Http\Controllers\Admin\ItemInventoryController as ItemInventory;
use App\Http\Controllers\Client\RefillController as Refill;
use App\Http\Controllers\Global\ItemsController as globalItems;
use App\Http\Controllers\Client\OrderCartController as order;
use App\Http\Controllers\Client\OrderCheckoutController as checkout;

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
    Route::post('/update-refills', [AdminRefill::class, 'updateRefilling']);

    Route::get('/get-refills', [Refill::class, 'getAllRefills']);
    Route::post('/add-refills', [Refill::class, 'addRefilling']);

    Route::post('/add-item-inventory', [ItemInventory::class, 'addItemInventory']);
    Route::get('/get-item-inventory', [ItemInventory::class, 'getAllItemInventory']);
    Route::post('/update-item-inventory', [ItemInventory::class, 'updateItemInventory']);
    Route::post('/delete-item-inventory/{id}', [ItemInventory::class, 'deleteItemInventory']);

    Route::get('/browser-items', [globalItems::class, 'getAllItem']);
    Route::post('/add-to-cart', [order::class, 'addToCart']);
    Route::post('/remove-to-cart/{id}', [order::class, 'removeOrderToCart']);

    Route::post('/checkout', [checkout::class, 'checkout']);
});