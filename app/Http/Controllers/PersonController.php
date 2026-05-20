<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Country;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\UsersDataTable;
use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;

class PersonController extends Controller
{

    public function __construct(
        private readonly UserService $userService
    ) {
        $this->authorizeResource(Person::class, 'person');
    }

    public function index(Request $request)
    {
        // return UsersDataTable::make($request)->render();
        return view('admin.people.index');
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

    public function store(StorePersonRequest $request)
    {
        try {
            $validated = $request->validated();
            
            $person = Person::create($validated);

            return redirect()
                ->route('admin.people.show', $person->id)
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::CREATED, 'Utilisateur')
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

    public function show(Person $person)
    {
        try {
            return view('admin.people.show', [
                'person' => $person->load('country'),
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

    public function edit(Person $person)
    {
        try {
            return view('admin.people.edit', [
                'person' => $person->load('country'),
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

    public function update(UpdatePersonRequest $request, Person $person)
    {
        try {
            $validated = $request->validated();

            
            $person->update($validated);

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::UPDATED, 'Utilisateur')
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

    public function showAddUserForm(Person $person)
    {
        try {
            if ($person->user) {
                return redirect()
                    ->back()
                    ->with('error', 'Cette personne a déjà un compte utilisateur.');
            }

            return view('admin.users.create', compact('person'));
        } catch (\Exception $e) {
            Log::error($e);
            
            return back()
                ->with('error', 'Erreur lors du chargement du formulaire.');
        }
    }

    public function addUser(StoreUserRequest $request, string $personId)
    {
        try {
            $person = Person::findOrFail($personId);

            if ($person->user) {
                return redirect()
                    ->back()
                    ->with('error', 'Cette personne a déjà un compte utilisateur.');
            }

            $data = $request->validated();
            $data['person_id'] = $person->id;

            $this->userService->create($data);

            return redirect()
                ->route('admin.people.show', $person->id)
                ->with('success', 'Compte utilisateur créé avec succès.');
        } catch (\Exception $e) {
            Log::error($e);
            
            return back()
                ->with('error', 'Erreur lors de la création du compte.')
                ->withInput();
        }
    }

    public function destroy(Person $person)
    {
        try {
            $user = $person->user;
            if ($user) {
                $user->delete();
            }
            $person->delete();

            return redirect()
                ->route('admin.people.index')
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::DELETED, 'Utilisateur')
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