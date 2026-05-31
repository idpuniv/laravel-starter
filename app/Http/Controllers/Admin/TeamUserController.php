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
    public function index(Team $team)
    {
        $users = $team->users()->paginate(15);

        return view('admin.teams.users.index', compact('team', 'users'));
    }

    public function create(Team $team)
    {
        $users = User::whereDoesntHave('teams', function ($q) use ($team) {
            $q->where('teams.id', $team->id);
        })->get();

        return view('admin.teams.users.create', compact('team', 'users'));
    }

    public function store(Request $request, Team $team)
    {
        $data = $request->validate([
            'users' => ['required', 'array'],
            'users.*' => ['exists:users,id']
        ]);

        $team->users()->syncWithoutDetaching($data['users']);

        return redirect()->route('admin.teams.users.index', $team);
    }

    public function destroy(Team $team, User $user)
    {
        $team->users()->detach($user->id);

        return back();
    }
}