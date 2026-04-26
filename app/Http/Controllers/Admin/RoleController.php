<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    /**
     * Liste des rôles
     */
    public function index()
    {
        try {
            $roles = Role::with('permissions')->paginate(10);

            return view('admin.roles.index', compact('roles'));

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement des rôles');
        }
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        try {
            $permissions = Permission::all();

            return view('admin.roles.create', compact('permissions'));

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web'
            ]);

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Rôle créé avec succès');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la création')
                ->withInput();
        }
    }

    /**
     * Détail
     */
    public function show(string $id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);

            return view('admin.roles.show', compact('role'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Rôle introuvable');
        }
    }

    /**
     * Formulaire édition
     */
    public function edit(string $id)
    {
        try {
            $role = Role::findOrFail($id);
            $permissions = Permission::all();

            return view('admin.roles.edit', compact('role', 'permissions'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Erreur lors du chargement');
        }
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            $role = Role::findOrFail($id);

            $role->update([
                'name' => $validated['name']
            ]);

            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Rôle mis à jour avec succès');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la mise à jour')
                ->withInput();
        }
    }

    /**
     * Suppression
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Rôle supprimé');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Erreur lors de la suppression');
        }
    }
}