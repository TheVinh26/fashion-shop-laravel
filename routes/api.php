<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);

// cart endpoints
Route::get('cart/{userId}', [CartController::class, 'show']);
Route::delete('cart/{userId}', [CartController::class, 'clear']);

// cart items
Route::post('cart-items', [CartItemController::class, 'store']);
Route::delete('cart-items/{cartItem}', [CartItemController::class, 'destroy']);

// order items
Route::post('order-items', [OrderItemController::class, 'store']);
