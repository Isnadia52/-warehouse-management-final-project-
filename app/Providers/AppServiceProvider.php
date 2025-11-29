<?php

// File: app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Models\Transaction;
use App\Policies\TransactionPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan Policy di sini (Global Authorization).
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pendaftaran Policy secara manual di Laravel 11
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
