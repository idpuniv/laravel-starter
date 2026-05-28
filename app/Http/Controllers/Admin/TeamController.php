<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    public function index()
    {
        try {
            $teams = Team::query()->latest()->get();

            return view('admin.teams.index', compact('teams'));
        } catch (\Exception $e) {
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR, 'Liste des équipes')
            );
        }
    }

    public function create()
    {
        try {
            return view('admin.teams.create');
        } catch (\Exception $e) {
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR, 'Création équipe')
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:teams,name'],
                'label' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'icon' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'string'],
            ]);

            Team::create($validated);

            return redirect()
                ->route('admin.teams.index')
                ->with(Status::SUCCESS, Status::message(Status::SUCCESS, 'Équipe'));
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

    public function edit(string $id)
    {
        try {
            $team = Team::findOrFail($id);

            return view('admin.teams.edit', compact('team'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.teams.index')
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Équipe'));
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $team = Team::findOrFail($id);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:teams,name,' . $team->id],
                'label' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'icon' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'string'],
            ]);

            $team->update($validated);

            return redirect()
                ->route('admin.teams.index')
                ->with(Status::SUCCESS, Status::message(Status::SUCCESS, 'Équipe'));
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

    public function destroy(string $id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->delete();

            return redirect()
                ->route('admin.teams.index')
                ->with(Status::SUCCESS, Status::message(Status::SUCCESS, 'Équipe'));
        } catch (\Exception $e) {
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR, 'Suppression équipe')
            );
        }
    }

    public function assignUsers(Request $request, string $teamId)
    {
        try {
            $validated = $request->validate([
                'users' => ['nullable', 'array'],
                'users.*' => ['exists:users,id'],
            ]);

            $team = Team::findOrFail($teamId);

            $team->users()->sync($validated['users'] ?? []);

            return redirect()
                ->route('admin.teams.edit', $team->id)
                ->with(Status::SUCCESS, Status::message(Status::SUCCESS, 'Utilisateurs équipe'));
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(Status::FAILED, Status::message(Status::FAILED));
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Assignation utilisateurs'));
        }
    }
}