<?php

// File: app/Policies/ProductPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Tentukan apakah user dapat melihat daftar produk.
     * Semua peran (Admin, Manager, Staff) diizinkan melihat.
     */
    public function viewAny(User $user): bool
    {
        // Semua role yang terlibat dalam gudang diizinkan melihat
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }

    /**
     * Tentukan apakah user dapat membuat produk.
     * Hanya Admin dan Manager yang dapat membuat.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Tentukan apakah user dapat mengedit produk.
     * Hanya Admin dan Manager yang dapat mengedit.
     */
    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Tentukan apakah user dapat menghapus produk.
     * Hanya Admin dan Manager yang dapat menghapus.
     */
    public function delete(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Tentukan apakah user dapat melihat produk spesifik.
     * Semua peran diizinkan melihat.
     */
    public function view(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }
}
