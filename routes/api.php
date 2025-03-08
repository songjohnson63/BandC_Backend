<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\FavoriteApiController;
use App\Http\Controllers\Api\CartApiController;


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
Route::post('/login', [AuthController::class, 'login']); // Login route

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']); // Logout route

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerApiController::class, 'index']);
    Route::post('/', [CustomerApiController::class, 'store']);
    Route::get('{id}', [CustomerApiController::class, 'show']);
    Route::put('{id}', [CustomerApiController::class, 'update']);
    Route::delete('{id}', [CustomerApiController::class, 'destroy']);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::post('/', [ProductApiController::class, 'store']);
    Route::get('{id}', [ProductApiController::class, 'show']);
    Route::put('{id}', [ProductApiController::class, 'update']);
    Route::delete('{id}', [ProductApiController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->post('/favorites/toggle', [FavoriteApiController::class, 'toggleFavorite']);
Route::middleware('auth:sanctum')->get('/favorites', [FavoriteApiController::class, 'getFavorites']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartApiController::class, 'addToCart']);
    Route::get('/cart', [CartApiController::class, 'getCartItems']);
    Route::delete('/cart/remove/{id}', [CartApiController::class, 'removeFromCart']);
});