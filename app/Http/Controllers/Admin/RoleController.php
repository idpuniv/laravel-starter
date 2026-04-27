<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Role;
use App\Models\Permission;
use App\Enums\Status;

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
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR)
            );
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
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR)
            );
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
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web'
        ]);

        $role->syncPermissions($validated['permissions']);

        return redirect()
            ->route('admin.roles.index')
            ->with(
                Status::SUCCESS,
                Status::message(Status::CREATED, 'Rôle')
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
            ->with(
                Status::ERROR,
                Status::message(Status::ERROR)
            )
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
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Rôle')
                );
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
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
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
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::UPDATED, 'Rôle')
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
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                )
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
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::DELETED, 'Rôle')
                );

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.roles.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
        }
    }
}