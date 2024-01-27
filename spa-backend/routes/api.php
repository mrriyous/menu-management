<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\UserDataController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\MenuOverviewController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Auth
Route::post('login', LoginController::class);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', [UserDataController::class, 'checkLoggedInUser']);
    
    Route::get('menu-overview', MenuOverviewController::class);

    Route::prefix('categories')->group(function() {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('get-hierarchically', [CategoryController::class, 'getHierarchically']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('products')->group(function() {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::prefix('discounts')->group(function() {
        Route::get('/', [DiscountController::class, 'index']);
        Route::get('/{id}', [DiscountController::class, 'show']);
        Route::post('/', [DiscountController::class, 'store']);
        Route::put('/{id}', [DiscountController::class, 'update']);
        Route::delete('/{id}', [DiscountController::class, 'destroy']);
    });
});
