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
    
    // Rute Dashboard Utama (Untuk default atau fallback)
    Route::get('/dashboard', function () {
        // Karena kita sudah menangani redirect di controller, 
        // rute ini hanya berfungsi sebagai default.
        return view('dashboard'); 
    })->name('dashboard');

    // Admin Dashboard & Rute
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        // Rute Admin lainnya akan diletakkan di sini
    });

    // Manager Dashboard & Rute
    Route::prefix('manager')->middleware('role:manager')->group(function () {
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');
        // Rute Manager lainnya akan diletakkan di sini
    });

    // Staff Dashboard & Rute
    Route::prefix('staff')->middleware('role:staff')->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        })->name('staff.dashboard');
        // Rute Staff lainnya akan diletakkan di sini
    });
    
    // Supplier Dashboard & Rute
    Route::prefix('supplier')->middleware('role:supplier')->group(function () {
        // Cek status approval di sini untuk menampilkan halaman pending
        Route::get('/dashboard', function () {
            if (!auth()->user()->is_approved) {
                return view('supplier.pending');
            }
            return view('supplier.dashboard');
        })->name('supplier.dashboard');
        // Rute Supplier lainnya akan diletakkan di sini
    });
});

require __DIR__.'/auth.php';
