<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Enums\Status;

class UserPermissionController extends Controller
{
    /**
     * Page d'édition des permissions utilisateur
     */
    public function edit(string $id)
    {
        try {
            $user = User::with(['roles', 'permissions'])->findOrFail($id);

            $rolePermissionsCollection = $user->getPermissionsViaRoles();
            $rolePermissions = $rolePermissionsCollection->pluck('label');
            $rolePermissionNames = $rolePermissionsCollection->pluck('name')->toArray();
            $permissions = Permission::query()
                ->whereNotIn('name', $rolePermissionNames)
                ->get();

            return view('admin.users.permissions.edit', compact(
                'user',
                'permissions',
                'rolePermissions'
            ));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Utilisateur'));
        }
    }

    /**
     * Mise à jour des permissions utilisateur
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name',
            ]);

            $user = User::findOrFail($id);

            $user->syncPermissions($validated['permissions'] ?? []);

            return redirect()
                ->route('admin.users.permissions.edit', $user->id)
                ->with(Status::SUCCESS, Status::message(Status::SUCCESS, 'Permissions'));
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(Status::FAILED, Status::message(Status::FAILED));
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Permissions'));
        }
    }

    /**
     * Affichage détail permissions utilisateur
     */
    public function show(string $id)
    {
        try {
            $user = User::with(['roles', 'permissions'])->findOrFail($id);

            return view('admin.users.permissions.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Utilisateur'));
        }
    }
}
