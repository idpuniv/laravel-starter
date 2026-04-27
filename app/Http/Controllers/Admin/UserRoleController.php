<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;
use App\Enums\Status;

class UserRoleController extends Controller
{
    /**
     * Liste des utilisateurs avec leurs rôles
     */
    public function index()
    {
        try {
            $users = User::with('roles')->paginate(10);

            return view('admin.users.roles.index', compact('users'));

        } catch (\Exception $e) {
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR, 'Utilisateurs')
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

            return view('admin.users.roles.create', compact('users', 'roles'));

        } catch (\Exception $e) {
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR, 'Formulaire')
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
                'user_id' => 'required|exists:users,id',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,name'
            ]);

            $user = User::findOrFail($validated['user_id']);

            $user->syncRoles($validated['roles']);

            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::SUCCESS, 'Rôles')
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
                    Status::message(Status::ERROR, 'Rôles')
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

            return view('admin.users.roles.show', compact('user'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Utilisateur')
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

            return view('admin.users.roles.edit', compact('user', 'roles'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Chargement')
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
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,name'
            ]);

            $user = User::findOrFail($id);

            $user->syncRoles($validated['roles']);

            return redirect()
                ->route('admin.users.roles.edit', $user->id)
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::SUCCESS, 'Rôles')
                );

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Rôles')
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
                    Status::SUCCESS,
                    Status::message(Status::SUCCESS, 'Rôles')
                );

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.roles.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Suppression')
                );
        }
    }
}