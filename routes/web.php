<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Arahkan user yang belum login ke halaman login
    return redirect()->route('login');
});

// Grup untuk semua yang sudah terotentikasi (Auth)
Route::middleware('auth')->group(function () {
    // Rute Profil Bawaan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.') // <--- KOREKSI: Tambahkan Name Prefix
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');
            
            // Product Management (CRUD Penuh)
            Route::resource('products', App\Http\Controllers\ProductController::class);
            // Transaction Management (Read & Approval Interface)
            Route::resource('transactions', App\Http\Controllers\TransactionController::class)->only(['index', 'show', 'update']);
            Route::resource('restock_orders', App\Http\Controllers\RestockOrderController::class);
            Route::resource('categories', App\Http\Controllers\CategoryController::class)->except(['edit', 'update', 'show']);
    });

    // Manager Dashboard & Rute
    Route::prefix('manager')
        ->middleware('role:manager')
        ->name('manager.') // <--- KOREKSI: Tambahkan Name Prefix
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('manager.dashboard');
            })->name('dashboard');
            
            // Product Management (CRUD Penuh)
            Route::resource('products', App\Http\Controllers\ProductController::class);
            // Transaction Management (Read & Approval Interface)
            Route::resource('transactions', App\Http\Controllers\TransactionController::class)->only(['index', 'show', 'update']);
            Route::resource('restock_orders', App\Http\Controllers\RestockOrderController::class);
            Route::resource('categories', App\Http\Controllers\CategoryController::class)->except(['edit', 'update', 'show']);
    });

    Route::prefix('staff')
        ->middleware('role:staff')
        ->name('staff.') // <--- KOREKSI: Tambahkan Name Prefix
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('staff.dashboard');
            })->name('dashboard');
            
            // Product List (Hanya Read: index & show)
            Route::resource('products', App\Http\Controllers\ProductController::class)->only(['index', 'show']);
            Route::resource('transactions', App\Http\Controllers\TransactionController::class)->except(['edit', 'update', 'destroy']);    });

    // Supplier Dashboard & Rute
    Route::prefix('supplier')
        ->middleware('role:supplier')
        ->name('supplier.') // <--- PENTING: Name Prefix di sini!
        ->group(function () {
            // Cek status approval di sini untuk menampilkan halaman pending
            Route::get('/dashboard', function () {
                if (!auth()->user()->is_approved) {
                    return view('supplier.pending');
                }
                return view('supplier.dashboard');
            })->name('dashboard');
            
            // Restock Management (Read & Update Status)
            // Jika Route::resource tidak memiliki ->names(), maka nama rute akan menjadi supplier.restock_orders.update
            Route::resource('restock_orders', App\Http\Controllers\RestockOrderController::class)->only(['index', 'show', 'update']);
    });
});

require __DIR__.'/auth.php';
