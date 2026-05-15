<?php

namespace App\Http\Controllers;

use App\DataTables\PeopleDataTable;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\UsersDataTable;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        return UsersDataTable::make($request)->render();
    }

    public function create()
    {
        try {
            return view('admin.people.create', [
                'countries' => Country::orderBy('name')->get(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR)
            );
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => ['nullable', 'exists:countries,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female'],
            'phone_code' => ['nullable', 'string', 'max:7'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            Person::create($validated);

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::CREATED, 'Personne')
                );
        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
        }
    }

    public function show(string $id)
    {
        try {
            return view('admin.people.show', [
                'person' => Person::with('country')->findOrFail($id),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
        }
    }

    public function edit(string $id)
    {
        try {
            return view('admin.people.edit', [
                'person' => Person::findOrFail($id),
                'countries' => Country::orderBy('name')->get(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
        }
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'country_id' => ['nullable', 'exists:countries,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'phone_code' => ['nullable', 'string', 'max:7'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $person = Person::findOrFail($id);

            $person->update($validated);

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::UPDATED, 'Personne')
                );
        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
        }
    }


    public function addUser(Request $request, string $personId)
    {
        $person = Person::findOrFail($personId);

        // Si déjà un user → on évite les doublons
        if ($person->user) {
            return redirect()
                ->back()
                ->with('error', 'Cette personne a déjà un compte utilisateur.');
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:active,inactive,banned'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string'],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'username' => $validated['username'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
            'team_id' => $validated['team_id'] ?? null,
            'person_id' => $person->id,
        ]);

        // Si tu utilises Spatie Permission
        if (!empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()
            ->route('admin.people.show', $person->id)
            ->with('success', 'Compte utilisateur créé avec succès.');
    }

    public function destroy(string $id)
    {
        try {
            $person = Person::findOrFail($id);

            $person->delete();

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::DELETED, 'Personne')
                );
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
        }
    }
}
