<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
// use App\Http\Controllers\Admin\AdminOrderController;
// use App\Http\Controllers\Admin\AdminStatisticController;

// use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('products', ProductController::class);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/about-us', function () { return view('about');})->name('about');

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');

// Register
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

// Product Detail
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add/{slug}', [CartController::class, 'add'])
        ->name('cart.add');

    Route::patch('/cart/item/{item}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/item/{item}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('order.store');

});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('products', AdminProductController::class);
        // Route::resource('products', AdminProductController::class);
        // Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

        Route::resource('orders', AdminOrderController::class);

        Route::get('statistics', [AdminStatisticController::class, 'index'])
            ->name('statistics');
    });

Route::get('/test-es', function () {
    return app(\App\Services\ElasticsearchService::class)
        ->client()
        ->info();
});
