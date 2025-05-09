<?php

namespace App\Policies;

use App\Models\User;
use App\Models\transaksipenjualan;
use Illuminate\Auth\Access\Response;

class transaksipenjualanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, transaksipenjualan $transaksiPenjualan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, transaksipenjualan $transaksiPenjualan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, transaksipenjualan $transaksiPenjualan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, transaksipenjualan $transaksiPenjualan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, transaksipenjualan $transaksiPenjualan): bool
    {
        return true;
    }
}