<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

// ==================== TRANG CHỦ ====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==================== SẢN PHẨM ====================
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// ==================== GIỎ HÀNG ====================
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

// ==================== XÁC THỰC ====================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
