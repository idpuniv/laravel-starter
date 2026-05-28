<?php

namespace App\Repositories;

use App\Contracts\Repositories\PermissionRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class SpatiePermissionRepository implements PermissionRepositoryInterface
{
    public function directPermissions(User $user): Collection
    {
        return $user->permissions;
    }

    public function rolePermissions(User $user): Collection
    {
        return $user->getPermissionsViaRoles();
    }

    public function assignablePermissions(User $user): Collection
    {
        $rolePermissionNames = $this->rolePermissions($user)
            ->pluck('name')
            ->toArray();

        return Permission::query()
            ->whereNotIn('name', $rolePermissionNames)
            ->get();
    }

    public function syncPermissions(
        User $user,
        array $permissions = []
    ): void {
        $user->syncPermissions($permissions);
    }
}