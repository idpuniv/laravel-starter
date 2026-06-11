<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Permissions\UserPermissions;
use App\Support\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:' . UserPermissions::LIST_ROLE)->only(['index']);
        $this->middleware('can:' . UserPermissions::CREATE_ROLE)->only(['create', 'store']);
        $this->middleware('can:' . UserPermissions::UPDATE_ROLE)->only(['edit', 'update']);
        $this->middleware('can:' . UserPermissions::VIEW_ROLE)->only(['show']);
        $this->middleware('can:' . UserPermissions::DELETE_ROLE)->only(['destroy']);
    }

    /**
     * Liste des utilisateurs avec leurs rôles
     */
    public function index()
    {
        try {
            $users = User::with('roles')->paginate(10);

            return view('admin.users.roles', compact('users'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    /**
     * Formulaire attribution rôle
     */
    public function create()
    {
        try {
            $users = User::all();
            $roles = Role::all();

            return view('admin.users.roles', compact('users', 'roles'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    /**
     * Attribution des rôles
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'roles' => ['required', 'array'],
                'roles.*' => ['exists:roles,name'],
            ]);

            $user = User::findOrFail($validated['user_id']);
            $user->syncRoles($validated['roles']);

            return redirect()
                ->route('admin.users.roles.index')
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

    /**
     * Voir rôles utilisateur
     */
    public function show(string $id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);

            return view('admin.users.roles', compact('user'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Flash::ERROR,
                    __('messages.not_found')
                );
        }
    }

    /**
     * Edition rôles
     */
    public function edit(string $id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            $roles = Role::all();

            return view('admin.users.roles', compact('user', 'roles'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }

    /**
     * Mise à jour rôles
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'roles' => ['required', 'array'],
                'roles.*' => ['exists:roles,name'],
            ]);

            $user = User::findOrFail($id);
            $user->syncRoles($validated['roles']);

            return redirect()
                ->route('admin.users.roles.edit', $user->id)
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

    /**
     * Suppression rôles utilisateur
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->syncRoles([]);

            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Flash::SUCCESS,
                    __('messages.deleted')
                );

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }
}