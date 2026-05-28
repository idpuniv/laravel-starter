<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function directPermissions(User $user): Collection;

    public function rolePermissions(User $user): Collection;

    public function assignablePermissions(User $user): Collection;

    public function syncPermissions(User $user, array $permissions = []): void;
}