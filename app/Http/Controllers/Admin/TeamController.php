<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    public function index()
    {
        try {
            $teams = Team::query()
                ->withCount('users')  // Ajoute le count des utilisateurs
                ->latest()
                ->paginate(15);

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

    public function show(string $id)
    {
        try {
            $team = Team::findOrFail($id);

            return view('admin.teams.show', compact('team'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.teams.index')
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
}
