<?php

use Illuminate\Support\Facades\Route;

// Unified Auth
use App\Http\Controllers\UnifiedAuthController;

<<<<<<< Updated upstream
Route::get('/', function () {
    return view('welcome');
=======
// Frontend Controllers
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\Auth\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

/*
|--------------------------------------------------------------------------
| Unified Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [UnifiedAuthController::class, 'showLoginForm'])->name('unified.login');
    Route::post('/login', [UnifiedAuthController::class, 'login']);
    Route::get('/register', [UnifiedAuthController::class, 'showRegisterForm'])->name('unified.register');
    Route::post('/register', [UnifiedAuthController::class, 'register']);
});

Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect old routes to unified auth
Route::redirect('/admin/login', '/login');
Route::redirect('/register-old', '/register');

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product_id?}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product_id?}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product_id?}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/get', [CartController::class, 'getCart'])->name('cart.get');

// Checkout
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderNumber}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes - Redirect to unified login
    Route::middleware('guest')->group(function () {
        Route::redirect('/login', '/login');
    });

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Categories
        Route::middleware('permission:categories.view')->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show']);
        });
        
        // Posts
        Route::middleware('permission:posts.view')->group(function () {
            Route::resource('posts', AdminPostController::class);
        });
        
        // Products
        Route::middleware('permission:products.view')->group(function () {
            Route::resource('products', AdminProductController::class);
        });
        
        // Orders
        Route::middleware('permission:orders.view')->group(function () {
            Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
            Route::put('orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
            Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
        });
        
        // Users
        Route::middleware('permission:users.view')->group(function () {
            Route::resource('users', UserController::class);
        });
        
        // Roles - Only Super Admin
        Route::middleware('permission:roles.view')->group(function () {
            Route::resource('roles', RoleController::class);
        });
        
        // Permissions - Only Super Admin
        Route::middleware('permission:permissions.view')->group(function () {
            Route::resource('permissions', PermissionController::class)->except(['show']);
        });
    });
>>>>>>> Stashed changes
});

// Test route for permissions
Route::get('/test-permissions', function () {
    return view('test-permissions');
})->middleware('admin')->name('test.permissions');
