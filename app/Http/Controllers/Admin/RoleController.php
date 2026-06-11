<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Support\Flash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Authorization des actions sur les rôles.
     *
     * Si nécessaire, activer les middlewares Spatie/permissions
     * ou policies Laravel pour sécuriser les actions CRUD.
     */
    public function __construct()
    {
        // Exemple middleware (Spatie Permission)
        // $this->middleware('can:' . RolePermissions::LIST)->only(['index']);
        // $this->middleware('can:' . RolePermissions::CREATE)->only(['create', 'store']);
        // $this->middleware('can:' . RolePermissions::UPDATE)->only(['edit', 'update']);
        // $this->middleware('can:' . RolePermissions::DELETE)->only(['destroy']);
        // $this->middleware('can:' . RolePermissions::VIEW)->only(['show']);

        // Alternative recommandée Laravel
        // $this->authorizeResource(Role::class, 'role');
    }

    public function index()
    {
        try {
            $roles = Role::with('permissions')->paginate(10);

            return view('admin.roles.index', compact('roles'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function create()
    {
        try {
            $permissions = Permission::all();

            return view('admin.roles.create', compact('permissions'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
                'permissions' => ['required', 'array', 'min:1'],
                'permissions.*' => ['exists:permissions,id'],
            ]);

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($validated['permissions']);

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    Flash::SUCCESS,
                    __('messages.created')
                );

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }

    public function show(Role $role)
    {
        try {
            $role->load('permissions');

            return view('admin.roles.show', compact('role'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    Flash::ERROR,
                    __('messages.not_found')
                );
        }
    }

    public function edit(Role $role)
    {
        try {
            $permissions = Permission::all();

            return view('admin.roles.edit', compact('role', 'permissions'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }

    public function update(Request $request, Role $role)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,id'],
            ]);

            $role->update([
                'name' => $validated['name'],
            ]);

            $role->syncPermissions($validated['permissions'] ?? []);

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    Flash::SUCCESS,
                    __('messages.updated')
                );

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    Flash::SUCCESS,
                    __('messages.deleted')
                );

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }
}