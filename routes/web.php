<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RestockOrderController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            
            Route::resource('products', ProductController::class);
            Route::resource('categories', CategoryController::class)->except(['edit', 'update', 'show']);
            Route::resource('transactions', TransactionController::class)->except(['edit']);
            Route::resource('restock_orders', RestockOrderController::class);
        });

    Route::prefix('manager')
        ->middleware('role:manager')
        ->name('manager.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            
            Route::resource('products', ProductController::class);
            Route::resource('categories', CategoryController::class)->except(['edit', 'update', 'show']);
            Route::resource('transactions', TransactionController::class)->except(['edit']);
            Route::get('restock_orders/{restock_order}/rate', [App\Http\Controllers\RestockOrderController::class, 'rate'])->name('restock_orders.rate');
            Route::resource('restock_orders', RestockOrderController::class);
        });

    Route::prefix('staff')
        ->middleware('role:staff')
        ->name('staff.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            
            Route::resource('products', ProductController::class)->only(['index', 'show']);
            Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'show']);
        });


    Route::prefix('supplier')
        ->middleware('role:supplier')
        ->name('supplier.')
        ->group(function () {
            Route::get('/dashboard', function () {
                if (!auth()->user()->is_approved) {
                    return view('supplier.pending');
                }
                return app(\App\Http\Controllers\DashboardController::class)->index();
            })->name('dashboard');
            
            Route::resource('restock_orders', RestockOrderController::class)->only(['index', 'show', 'update']);
        });
});

require __DIR__.'/auth.php';
