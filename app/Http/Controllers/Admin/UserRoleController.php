<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;

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
            return back()->with('error', 'Erreur lors du chargement des utilisateurs');
        }
    }

    /**
     * Formulaire d’attribution de rôle à un utilisateur
     */
    public function create()
    {
        try {
            $users = User::all();
            $roles = Role::all();

            return view('admin.users.roles.create', compact('users', 'roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Attribuer un rôle à un utilisateur
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

            // Remplace les anciens rôles
            $user->syncRoles($validated['roles']);

            return redirect()
                ->route('admin.users.roles.index')
                ->with('success', 'Rôles attribués avec succès');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de l’attribution des rôles')
                ->withInput();
        }
    }

    /**
     * Voir les rôles d’un utilisateur
     */
    public function show(string $id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);

            return view('admin.users.roles.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.roles.index')
                ->with('error', 'Utilisateur introuvable');
        }
    }

    /**
     * Formulaire modification rôles utilisateur
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
                ->with('error', 'Erreur lors du chargement');
        }
    }

    /**
     * Mise à jour des rôles utilisateur
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
                ->with('success', 'Rôles mis à jour');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Throwable $e) {

            return back()
                ->with('error', 'Erreur système lors de la mise à jour')
                ->withInput();
        }
    }

    /**
     * Retirer tous les rôles d’un utilisateur
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->syncRoles([]); // supprime tous les rôles

            return redirect()
                ->route('admin.users.roles.index')
                ->with('success', 'Rôles supprimés');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.roles.index')
                ->with('error', 'Erreur lors de la suppression');
        }
    }
}
