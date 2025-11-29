<?php

// File: app/Policies/TransactionPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    /**
     * Tentukan apakah user dapat melihat daftar transaksi.
     * Admin, Manager, Staff diizinkan melihat.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }

    /**
     * Tentukan apakah user dapat membuat transaksi.
     * Hanya Staff yang dapat membuat.
     */
    public function create(User $user): bool
    {
        return $user->role === 'staff';
    }

    /**
     * Tentukan apakah user dapat menyetujui transaksi (untuk fungsi approval).
     * Hanya Manager dan Admin (sebagai superuser) yang dapat menyetujui.
     */
    public function approve(User $user, Transaction $transaction): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Tentukan apakah user dapat mengedit/menghapus transaksi.
     * Hanya Staff pembuat transaksi DAN statusnya masih Pending.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        // Hanya Staff yang membuatnya dan statusnya masih Pending
        return $user->id === $transaction->staff_id && $transaction->status === 'Pending';
    }
    
    // Admin selalu bisa melihat detail (view) dan menghapus/mengedit jika perlu (melalui before/superuser check)

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
