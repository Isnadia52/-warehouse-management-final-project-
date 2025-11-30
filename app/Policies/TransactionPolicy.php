<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }


    public function create(User $user): bool
    {
        return $user->role === 'staff';
    }

    public function approve(User $user, Transaction $transaction): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

 
    public function update(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->staff_id && $transaction->status === 'Pending';
    }
    

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transaction $transaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return false;
    }
}
