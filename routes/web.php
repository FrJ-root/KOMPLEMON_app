<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\StatisticsController;
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

Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::middleware(['role:administrateur'])->group(function () {

        Route::resource('coupons', CouponController::class);
        
        Route::get('/settings', [SettingController::class, 'index'])
        ->name('admin.settings.index');
        Route::post('/settings', [SettingController::class, 'update'])
        ->name('admin.settings.update');
        
        Route::resource('users', UserController::class);
        
        Route::post('/users/block-permissions', [UserController::class, 'blockPermissions'])
        ->name('users.block-permissions');
        
        Route::get('/statistics', [StatisticController::class, 'index'])
        ->name('admin.statistics.index');

        Route::get('/statistics/sales', [StatisticController::class, 'sales'])
        ->name('admin.statistics.sales');

        Route::get('/statistics/users', [StatisticController::class, 'users'])
        ->name('admin.statistics.users');

        Route::get('/statistics/products', [StatisticController::class, 'products'])
        ->name('admin.statistics.products');
    });
    
    Route::middleware(['role:administrateur,gestionnaire_produits'])->group(function () {

        Route::resource('products', ProductController::class);

        Route::resource('categories', CategoryController::class);

        Route::resource('media', MediaController::class);

        Route::delete('/media/bulk-destroy', [MediaController::class, 'bulkDestroy'])
        ->name('media.bulk-destroy');
        
        Route::post('/media/{medium}/set-as-main', [MediaController::class, 'setAsMainImage'])
        ->name('media.set-as-main');
    });
    
    Route::middleware(['role:administrateur,gestionnaire_commandes'])->group(function () {

        Route::resource('orders', OrderController::class);

        Route::resource('clients', ClientController::class);
        
        Route::match(['get', 'post'], '/orders/export', [OrderController::class, 'export'])->name('admin.orders.export');
        Route::match(['get', 'post'], '/orders/{order}/export', [OrderController::class, 'exportSingle'])->name('orders.export.single');
    });
    
    Route::middleware(['role:administrateur,editeur_contenu'])->group(function () {

        Route::resource('articles', ArticleController::class);

        Route::resource('testimonials', TestimonialController::class);
        
        Route::patch('/testimonials/{testimonial}/toggle-approval', [TestimonialController::class, 'toggleApproval'])
            ->name('testimonials.toggleApproval');

    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/statistics', [StatisticsController::class, 'index'])
    ->name('statistics.index');
    
    Route::get('/sales/evolution', [SalesController::class, 'showSalesEvolution'])
    ->name('sales.evolution');

    Route::get('/sales/evolution/data', [SalesController::class, 'getSalesEvolution'])
    ->name('sales.evolution.data');
});

Route::get('/admin/orders/{order}/print', function(\App\Models\Order $order) {

    $order->load(['client', 'items.product']);

    return view('admin.orders.print', compact('order'));

})->middleware(['auth', 'role:administrateur,gestionnaire_commandes'])->name('orders.print');