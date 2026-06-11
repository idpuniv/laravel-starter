<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function find(string $id): User
    {
        return User::findOrFail($id);
    }

    public function update(string $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function delete(string $id): void
    {
        $user = $this->find($id);
        $user->delete(); // soft delete si SoftDeletes est activé
    }

    public function forceDelete(string $id): void
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
    }

    /**
     * RESTORE USER (Soft Delete)
     */
    public function restore(string $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return $user;
    }
}