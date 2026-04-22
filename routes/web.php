<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboard;
use App\Http\Controllers\Vendor\ProductController as VendorProduct;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\VendorController as AdminVendor;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\CustomerController as AdminCustomer;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;

// =============================================
// PUBLIC ROUTES (Customer Facing)
// =============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.show');

// =============================================
// CUSTOMER AUTH
// =============================================
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/register',  [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.post');
    Route::get('/login',     [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [CustomerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',   [CustomerAuthController::class, 'logout'])->name('logout');
});

// =============================================
// CUSTOMER PROTECTED ROUTES
// =============================================
Route::prefix('customer')->name('customer.')->middleware('customer.auth')->group(function () {
    Route::get('/cart',          [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add',     [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove',  [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update',  [CartController::class, 'update'])->name('cart.update');
    Route::post('/checkout',     [CartController::class, 'checkout'])->name('checkout');
    // Checkout & Orders
Route::get('/checkout',          [\App\Http\Controllers\Customer\CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/inquiry', [\App\Http\Controllers\Customer\CheckoutController::class, 'inquiry'])->name('checkout.inquiry');
Route::post('/checkout/pay',     [\App\Http\Controllers\Customer\CheckoutController::class, 'processPayment'])->name('checkout.pay');
Route::get('/order/{orderNumber}/success', [\App\Http\Controllers\Customer\CheckoutController::class, 'success'])->name('order.success');

});

// =============================================
// VENDOR AUTH
// =============================================
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/login',     [VendorAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [VendorAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',   [VendorAuthController::class, 'logout'])->name('logout');
});

// =============================================
// VENDOR PROTECTED ROUTES
// =============================================
Route::prefix('vendor')->name('vendor.')->middleware('vendor.auth')->group(function () {
    Route::get('/dashboard', [VendorDashboard::class, 'index'])->name('dashboard');
    Route::resource('/products', VendorProduct::class)->names([
        'index'   => 'products.index',
        'create'  => 'products.create',
        'store'   => 'products.store',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);
});

// =============================================
// ADMIN AUTH
// =============================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',     [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',   [AdminAuthController::class, 'logout'])->name('logout');
});

// =============================================
// ADMIN PROTECTED ROUTES
// =============================================
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Vendors CRUD
    Route::resource('/vendors', AdminVendor::class)->names([
        'index'   => 'vendors.index',
        'create'  => 'vendors.create',
        'store'   => 'vendors.store',
        'show'    => 'vendors.show',
        'edit'    => 'vendors.edit',
        'update'  => 'vendors.update',
        'destroy' => 'vendors.destroy',
    ]);

    // Products CRUD
    Route::resource('/products', AdminProduct::class)->names([
        'index'   => 'products.index',
        'create'  => 'products.create',
        'store'   => 'products.store',
        'show'    => 'products.show',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);

    // Categories CRUD
    Route::resource('/categories', AdminCategory::class)->names([
        'index'   => 'categories.index',
        'create'  => 'categories.create',
        'store'   => 'categories.store',
        'show'    => 'categories.show',
        'edit'    => 'categories.edit',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    // Customers — read only
    Route::get('/customers', [AdminCustomer::class, 'index'])->name('customers.index');

    // Orders — read only
    Route::get('/orders', [AdminOrder::class, 'index'])->name('orders.index');
});
