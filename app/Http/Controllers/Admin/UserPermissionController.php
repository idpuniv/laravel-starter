<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\PermissionRepositoryInterface;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Permissions\UserPermissions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserPermissionController extends Controller
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {
        $this->middleware('can:' . UserPermissions::UPDATE_PERMISSION)
            ->only(['edit', 'update']);

        $this->middleware('can:' . UserPermissions::VIEW_PERMISSION)
            ->only(['show']);
    }

    /**
     * Page d'édition des permissions utilisateur
     */
    public function edit(string $id)
    {
        try {
            $user = User::with(['roles', 'permissions'])
                ->findOrFail($id);

            $rolePermissions = $this->permissionRepository
                ->rolePermissions($user)
                ->pluck('label');

            $permissions = $this->permissionRepository
                ->assignablePermissions($user);

            return view('admin.users.permissions', compact(
                'user',
                'permissions',
                'rolePermissions'
            ));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Utilisateur')
                );
        }
    }

    /**
     * Mise à jour des permissions utilisateur
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,name'],
            ]);

            $user = User::findOrFail($id);

            $this->permissionRepository->syncPermissions(
                $user,
                $validated['permissions'] ?? []
            );

            return redirect()
                ->route('admin.users.permissions.edit', $user->id)
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::SUCCESS, 'Permissions')
                );
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(
                    Status::FAILED,
                    Status::message(Status::FAILED)
                );
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Permissions')
                );
        }
    }

    /**
     * Affichage détail permissions utilisateur
     */
    public function show(string $id)
    {
        try {
            $user = User::with(['roles', 'permissions'])
                ->findOrFail($id);

            return view('admin.users.permissions', compact('user'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Utilisateur')
                );
        }
    }
}