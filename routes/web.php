<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\TestimonialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes with role middleware
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Administrateur routes
    Route::middleware(['role:administrateur'])->group(function () {
        Route::resource('coupons', CouponController::class);
        
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
        
        Route::resource('users', UserController::class);
        
        // Add new route for blocking permissions
        Route::post('/users/block-permissions', [UserController::class, 'blockPermissions'])->name('users.block-permissions');
        
        Route::get('/statistics', [StatisticController::class, 'index'])->name('admin.statistics.index');
        Route::get('/statistics/sales', [StatisticController::class, 'sales'])->name('admin.statistics.sales');
        Route::get('/statistics/users', [StatisticController::class, 'users'])->name('admin.statistics.users');
        Route::get('/statistics/products', [StatisticController::class, 'products'])->name('admin.statistics.products');
    });
    
    // Product Manager routes
    Route::middleware(['role:administrateur,gestionnaire_produits'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('media', MediaController::class);
    });
    
    // Order Manager routes
    Route::middleware(['role:administrateur,gestionnaire_commandes'])->group(function () {
        Route::resource('orders', OrderController::class);
        Route::resource('customers', CustomerController::class);
        Route::get('/export', [OrderController::class, 'export'])->name('admin.orders.export');
    });
    
    // Content Editor routes
    Route::middleware(['role:administrateur,editeur_contenu'])->group(function () {
        Route::resource('articles', ArticleController::class);
        Route::resource('testimonials', TestimonialController::class);
    });
});