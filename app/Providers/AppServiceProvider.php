<?php

// File: app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Import Gate Facade untuk Policy
use App\Models\Product; // Import Model Product
use App\Policies\ProductPolicy; // Import Policy yang baru dibuat

class AppServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan Policy di sini (Global Authorization).
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
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
