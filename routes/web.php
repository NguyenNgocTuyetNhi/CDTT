


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;

// ==================== ADMIN ROUTE GROUP ====================
Route::prefix('admin')->as('admin.')->group(function () {
    // HOME + DASHBOARD
    Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // USERS
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // PRODUCTS
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // BANNERS
    Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{id}/edit', [BannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{id}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');
    Route::post('/banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');

    // BRANDS
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

    // MENUS
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');

    // CATEGORIES
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // POSTS
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    
    // ORDERS
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
});

// ==================== TRANG CHỦ ====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==================== SẢN PHẨM ====================
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// ==================== GIỎ HÀNG ====================
Route::get('/cart', [\App\Http\Controllers\Shop\CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{id}', [\App\Http\Controllers\Shop\ProductController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\Shop\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [\App\Http\Controllers\Shop\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [\App\Http\Controllers\Shop\CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-coupon', [\App\Http\Controllers\Shop\CartController::class, 'applyCoupon'])->name('cart.applyCoupon');

// ==================== THANH TOÁN ====================
Route::post('/checkout/process', [\App\Http\Controllers\Shop\CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{id}', [\App\Http\Controllers\Shop\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout', [\App\Http\Controllers\Shop\CheckoutController::class, 'show'])->name('shop.checkout');
Route::post('/shop/placeorder', [\App\Http\Controllers\Shop\CheckoutController::class, 'placeOrder'])->name('shop.placeorder');


// ==================== ĐƠN HÀNG CỦA TÔI ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [\App\Http\Controllers\Shop\OrderController::class, 'index'])->name('my.orders');
    Route::get('/my-orders/{id}', [\App\Http\Controllers\Shop\OrderController::class, 'show'])->name('my.orders.show');
    Route::get('/momo-return', [\App\Http\Controllers\Shop\CheckoutController::class, 'show'])->name('momo.return');
    Route::get('/momo-callback', [\App\Http\Controllers\Shop\CheckoutController::class, 'momoCallback'])->name('momo.callback');
    // PROFILE
    Route::get('/profile', [\App\Http\Controllers\Shop\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [\App\Http\Controllers\Shop\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Shop\ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ==================== XÁC THỰC ====================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== ADMIN - SẢN PHẨM ====================
Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
Route::get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
Route::put('/admin/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

// ==================== ADMIN - BANNER ====================
Route::get('/admin/banners', [BannerController::class, 'index'])->name('admin.banners.index');
Route::get('/admin/banners/create', [BannerController::class, 'create'])->name('admin.banners.create');
Route::post('/admin/banners', [BannerController::class, 'store'])->name('admin.banners.store');
Route::get('/admin/banners/{id}/edit', [BannerController::class, 'edit'])->name('admin.banners.edit');
Route::put('/admin/banners/{id}', [BannerController::class, 'update'])->name('admin.banners.update');
Route::delete('/admin/banners/{id}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');

// ==================== ADMIN - BRAND ====================
Route::get('/admin/brands', [BrandController::class, 'index'])->name('admin.brands.index');

// ==================== ADMIN - MENU ====================
Route::get('/admin/menus', [MenuController::class, 'index'])->name('admin.menus.index');

// ==================== ADMIN - CATEGORY ====================
Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');

// ==================== ADMIN - POST ====================
Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index');


