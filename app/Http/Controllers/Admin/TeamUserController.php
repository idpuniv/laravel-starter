<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamUserController extends Controller
{
    /**
     * Liste des utilisateurs de l'équipe
     */
    public function index(string $teamId)
    {
        try {
            $team = Team::with('users')->findOrFail($teamId);

            return view('admin.teams.users.index', compact('team'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.teams.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Équipe')
                );
        }
    }

    /**
     * Formulaire d'assignation des utilisateurs
     */
    public function edit(string $teamId)
    {
        try {
            $team = Team::with('users')->findOrFail($teamId);

            $users = User::query()
                ->latest()
                ->get();

            $teamUsers = $team->users
                ->pluck('id')
                ->toArray();

            return view('admin.teams.users.edit', compact(
                'team',
                'users',
                'teamUsers'
            ));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.teams.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Équipe')
                );
        }
    }

    /**
     * Mise à jour des utilisateurs de l'équipe
     */
    public function update(Request $request, string $teamId)
    {
        try {
            $validated = $request->validate([
                'users' => ['nullable', 'array'],
                'users.*' => ['exists:users,id'],
            ]);

            $team = Team::findOrFail($teamId);

            // Laravel standard pivot sync
            $team->users()->sync(
                $validated['users'] ?? []
            );

            return redirect()
                ->route('admin.teams.users.edit', $team->id)
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::SUCCESS, 'Utilisateurs équipe')
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
                    Status::message(Status::ERROR, 'Utilisateurs équipe')
                );
        }
    }

    /**
     * Retirer un utilisateur de l'équipe
     */
    public function destroy(string $teamId, string $userId)
    {
        try {
            $team = Team::findOrFail($teamId);

            $team->users()->detach($userId);

            return back()->with(
                Status::SUCCESS,
                Status::message(Status::SUCCESS, 'Utilisateur retiré')
            );
        } catch (\Exception $e) {
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR, 'Suppression utilisateur')
            );
        }
    }
}