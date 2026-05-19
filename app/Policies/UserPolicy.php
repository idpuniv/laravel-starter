<?php

namespace App\Policies;

use App\Models\User;
use App\Roles\Roles;
use App\Permissions\UserPermissions;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(UserPermissions::LIST);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->can(UserPermissions::VIEW);
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
    public function update(User $user, User $model): bool
    {
       return $user->id === $model->id || $user->can(UserPermissions::UPDATE);
    }

    public function updateRole(User $user, User $model)
    {
        return $user->can(UserPermissions::UPDATE_ROLE);
    }

    public function updatePermission(User $user, User $model)
    {
        return $user->can(UserPermissions::UPDATE_PERMISSION);

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // ROOT ne peut jamais se supprimer lui-même
        if ($user->hasRole(Roles::ROOT) && $user->id === $model->id) {
            return false;
        }

        // ✔ Un utilisateur peut supprimer SON compte
        if ($user->id === $model->id) {
            return true;
        }

        // Personne ne peut supprimer ROOT
        if ($model->hasRole(Roles::ROOT)) {
            return false;
        }

        // ✔ ROOT peut supprimer tous les autres
        if ($user->hasRole(Roles::ROOT)) {
            return true;
        }

        // Sinon interdit
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole(Roles::ROOT);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole(Roles::ROOT);
    }
}
