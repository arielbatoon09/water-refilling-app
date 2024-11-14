<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\GallonTypeController as GallonType;
use App\Http\Controllers\Admin\RefillController as AdminRefill;
use App\Http\Controllers\Admin\ItemInventoryController as ItemInventory;
use App\Http\Controllers\Admin\UserController as User;
use App\Http\Controllers\Client\RefillController as Refill;
use App\Http\Controllers\Client\AccountSettingController as Account;
use App\Http\Controllers\Global\ItemsController as GlobalItems;
use App\Http\Controllers\Client\CartController as Cart;
use App\Http\Controllers\Global\ProductController as Products;
use App\Http\Controllers\Client\OrdersController as Checkout;
use App\Http\Controllers\Client\ReviewController as Review;

// Global Access
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/get-all-gallon', [GallonType::class, 'getAllGallonType']);
    Route::post('/add-gallon-type', [GallonType::class, 'AddGallonType']);
    Route::post('/update-gallon-type', [GallonType::class, 'updateGallonTypes']);
    Route::post('/delete-gallon-type/{id}', [GallonType::class, 'deleteGallonType']);
    Route::post('/update-refills', [AdminRefill::class, 'updateRefilling']);
    Route::get('/get-all-refills', [AdminRefill::class, 'getAllRefills']);
    Route::get('/get-selected-gallon/{id}', [GallonType::class, 'getSelectedGallon']);

    Route::get('/get-refills-by-uid', [Refill::class, 'getRefillsByUID']);
    Route::post('/add-refills', [Refill::class, 'addRefilling']);
    Route::post('/pay-refills', [Refill::class, 'payRefillOrder']);
    Route::post('/paid-refill', [Refill::class, 'paidRefillOrder']);
    Route::post('/cancel-refill/{id}', [Refill::class, 'cancelOrder']);
    Route::post('/complete-refill/{id}', [Refill::class, 'completeOrder']);

    Route::post('/add-item-inventory', [ItemInventory::class, 'addItemInventory']);
    Route::get('/get-item-inventory', [ItemInventory::class, 'getAllItemInventory']);
    Route::post('/update-item-inventory', [ItemInventory::class, 'updateItemInventory']);
    Route::post('/delete-item-inventory/{id}', [ItemInventory::class, 'deleteItemInventory']);
    
    Route::get('/get-products', [Products::class, 'getProducts']);
    Route::get('/get-products-by-pid/{id}', [Products::class, 'selectedProduct']);
    Route::post('/add-product', [Products::class, 'addProductItem']);
    Route::post('/update-product/{id}', [Products::class, 'updateProduct']);
    Route::post('/remove-product/{id}', [Products::class, 'removeProduct']);

    Route::get('/browser-items', [GlobalItems::class, 'getAllItem']);
    Route::post('/add-to-cart', [Cart::class, 'addToCart']);
    Route::get('/get-uid-cart', [Cart::class, 'getAllUIDCart']);
    Route::post('/update-cart-qty', [Cart::class, 'updateCartOrderQuantity']);
    Route::post('/remove-to-cart/{id}', [Cart::class, 'removeOrderToCart']);

    Route::post('/checkout', [Checkout::class, 'checkout']);
    Route::post('/checkout/pay-now', [Checkout::class, 'generatePayNow']);
    Route::post('/checkout/paid', [Checkout::class, 'updateOrderStatus']);
    Route::post('/checkout/remove/{id}', [Checkout::class, 'removeOrderByCartId']);
    Route::get('/get-orders-uid', [Checkout::class, 'getOrdersUID']);

    Route::post('/add-review', [Review::class, 'addReview']);

    Route::get('/get-all-user', [User::class, 'getAllUser']);
    Route::get('/get-user-by-uid/{id}', [User::class, 'getUserById']);
    Route::post('/remove-user/{id}', [User::class, 'removeUser']);
    Route::post('/update-user', [User::class, 'updateUser']);

    Route::get('/get-user-info',[Account::class, 'getUserInformation']);
    Route::get('/get-user-address',[Account::class, 'getAddressInformation']);
    Route::post('/add-user-address',[Account::class, 'addUserAddress']);
    Route::post('/change-user-info',[Account::class, 'updateUserInfo']);
    Route::post('/change-user-password',[Account::class, 'changePassword']);
    Route::post('/change-user-info',[Account::class, 'updateUserInfo']);
    Route::post('/change-user-address',[Account::class, 'changeAddress']);
});