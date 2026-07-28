<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;

class LabelPolicy
{
    /**
     * Determine whether the user can view any labels.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create labels.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the label.
     */
    public function update(User $user, Label $label): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the label.
     */
    public function delete(User $user, Label $label): bool
    {
        return true;
    }
}
