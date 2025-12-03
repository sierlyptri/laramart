<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController; 

use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'store'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [OrderController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});