<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\DiscountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['domain' => env('APP_URL')], function () {
    Route::prefix('/v1')->group(function (){

        // product routes
        Route::prefix('/product')->group(function(){
            Route::post('/create', [ProductController::class, 'create'])->name('product.create');
        });

        // order routes
        Route::prefix('/order')->group(function(){
            Route::get('/list', [OrderController::class, 'orders'])->name('orders.list');
            Route::post('/create', [OrderController::class, 'create'])->name('orders.create');
            Route::delete('/delete/{id}', [OrderController::class, 'delete'])->name('orders.delete');
            Route::get('/discount/{id}', [DiscountController::class, 'index'])->name('discount.search');
        });
    });
});



