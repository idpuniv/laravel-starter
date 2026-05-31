<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserTeamController extends Controller
{
    /**
     * Afficher les utilisateurs d'une équipe
     */
    public function index(string $userId)
    {
        try {
            $user = User::with('teams')->findOrFail($userId);
            $teams = $user->teams()->paginate(15);

            return view('admin.people.teams.index', compact('user', 'teams'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.teams.index')
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Équipe'));
        }
    }

    /**
     * Formulaire d’assignation utilisateurs
     */
    public function edit(User $user, Team $team)
    {
        try {
            $teams = $user->load('teams');

            return view('admin.users.teams.edit', compact('user','teams'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.teams.index')
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Équipe'));
        }
    }

    /**
     * Mise à jour (STANDARD LARAVEL : sync pivot dans update)
     */
    public function update(Request $request, User $user, Team $team)
    {
        try {
            $validated = $request->validate([
                'users' => ['nullable', 'array'],
                'users.*' => ['exists:users,id'],
            ]);

            // STANDARD LARAVEL
            $team->users()->sync($validated['users'] ?? []);

            return redirect()
                ->route('admin.teams.users.edit', $team->id)
                ->with(Status::SUCCESS, Status::message(Status::SUCCESS, 'Utilisateurs équipe'));

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(Status::FAILED, Status::message(Status::FAILED));

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Équipe'));
        }
    }

    /**
     * Retirer UN utilisateur (DELETE standard relation)
     */
    public function destroy(string $teamId, string $userId)
    {
        try {
            $team = Team::findOrFail($teamId);

            // STANDARD LARAVEL
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