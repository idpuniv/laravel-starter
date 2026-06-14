<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Permissions\CommentPermissions;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CommentPermissions::LIST);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->can(CommentPermissions::VIEW);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(CommentPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user?->id === $comment->user_id || $user->can(CommentPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user?->id === $comment->user_id || $user->can(CommentPermissions::DELETE);
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, Comment $comment): bool
    {
        return $user->can(CommentPermissions::MODERATE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->can(CommentPermissions::RESTORE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->can(CommentPermissions::FORCE_DELETE);
    }
}
