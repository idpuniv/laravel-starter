<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    /**
     * Page d'édition des permissions utilisateur
     */
    public function edit(string $id)
    {
        try {
            $user = User::with(['roles', 'permissions'])->findOrFail($id);

            // permissions déjà données via les rôles
            $rolePermissions = $user->getPermissionsViaRoles()
                ->pluck('name')
                ->toArray();

            // permissions assignables (hors rôles)
            $permissions = Permission::whereNotIn('name', $rolePermissions)->get();

            return view('admin.users.permissions.edit', compact(
                'user',
                'permissions',
                'rolePermissions'
            ));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Utilisateur introuvable');
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
                ->with('success', 'Permissions mises à jour avec succès');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la mise à jour des permissions')
                ->withInput();
        }
    }

    /**
     * Affichage optionnel (détail user permissions)
     */
    public function show(string $id)
    {
        try {
            $user = User::with(['roles', 'permissions'])->findOrFail($id);

            return view('admin.users.permissions.show', compact('user'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Utilisateur introuvable');
        }
    }
}