<?php


namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }


    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }


    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }


    public function delete(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }


    public function view(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }
}
