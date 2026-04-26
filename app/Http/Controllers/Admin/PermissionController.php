<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Permission;
use App\Models\Module;

class PermissionController extends Controller
{
    /**
     * Liste des permissions
     */
    public function index()
    {
        try {
            $permissions = Permission::with('module')
                ->orderBy('module_id')
                ->paginate(10);

            return view('admin.permissions.index', compact('permissions'));

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement des permissions');
        }
    }

    /**
     * Détail permission
     */
    public function show(string $id)
    {
        try {
            $permission = Permission::with('module')->findOrFail($id);

            return view('admin.permissions.show', compact('permission'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Permission introuvable');
        }
    }

    /**
     * Formulaire édition (label uniquement)
     */
    public function edit(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $modules = Module::where('is_active', true)->get();

            return view('admin.permissions.edit', compact('permission', 'modules'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Erreur lors du chargement');
        }
    }

    /**
     * Mise à jour (ONLY label + module)
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'label' => 'nullable|string|max:255',
                'module_id' => 'nullable|exists:modules,id'
            ]);

            $permission = Permission::findOrFail($id);

            $module = isset($validated['module_id'])
                ? Module::find($validated['module_id'])
                : null;

            $permission->update([
                'label' => $validated['label'] ?? $permission->label,
                'module_id' => $validated['module_id'],
                'module_slug' => $module?->slug
            ]);

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission mise à jour avec succès');

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
}