<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\UsersDataTable;
use App\Models\Role;
use App\Models\Person;
use App\Models\User;
use App\Models\Country;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Enums\Status;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Config;
use App\Permissions\UserPermissions;

class UserController extends Controller
{


    public function __construct(
        private readonly UserService $userService
    ) {
        // $this->middleware('can:' . UserPermissions::LIST)->only(['index']);
        // $this->middleware('can:' . UserPermissions::CREATE)->only(['create', 'store']);
        // $this->middleware('can:' . UserPermissions::UPDATE)->only(['edit', 'update', 'changeStatus']);
        // $this->middleware('can:' . UserPermissions::DELETE)->only(['destroy', 'forceDestroy']);
        // $this->middleware('can:' . UserPermissions::VIEW)->only(['show']);
        // $this->authorizeResource(User::class, 'user');
    }

    private function isTeamsEnabled(): bool
    {
        return Config::get('permission.teams', false);
    }

    public function index(Request $request)
    {
        return UsersDataTable::make($request)->render();
    }

    public function create(Request $request)
    {
        try {
            $person = null;

            // Si on crée un compte pour une personne existante
            if ($request->has('person_id')) {
                $person = Person::findOrFail($request->person_id);

                // Si la personne a déjà un compte, rediriger vers l'édition
                if ($person->user) {
                    return redirect()
                        ->route('admin.users.edit', $person->id)
                        ->with(Status::SUCCESS, 'Cette personne a déjà un compte utilisateur.');
                }
            }

            $countries = Country::all();
            $roles = Role::all();
            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.create', compact('teams', 'countries', 'roles', 'person'));
        } catch (\Exception $e) {
            return back()->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }

    public function store(StoreUserRequest $request)
    {
        // dd($request->all());
        try {
            $this->userService->create($request->validated());

            return redirect()
                ->route('admin.users.index')
                ->with(Status::SUCCESS, Status::message(Status::CREATED, 'Utilisateur'));
        } catch (\Exception $e) {
            return back()
                ->with(Status::ERROR, Status::message(Status::ERROR))
                ->withInput();
        }
    }

    public function show(string $personId)
    {
        try {
            $person = $this->userService->find($personId);

            return view('admin.users.show', compact('person'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR, 'affichage de la personne'));
        }
    }
    public function edit(string $id)
    {
        try {
            $person = $this->userService->find($id);
            $countries = Country::all();

            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.edit', compact('person', 'teams', 'countries'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $this->userService->update($id, $request->validated());

            return redirect()
                ->route('admin.users.index')
                ->with(Status::SUCCESS, Status::message(Status::UPDATED, 'Utilisateur'));
        } catch (\Exception $e) {
            return back()
                ->with(Status::ERROR, Status::message(Status::ERROR))
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->userService->delete($id);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::SUCCESS, Status::message(Status::DELETED, 'Utilisateur'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }

    // Pour suppression définitive
    public function forceDestroy(string $id)
    {
        try {
            $this->userService->forceDelete($id);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::SUCCESS, 'Utilisateur supprimé définitivement');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, 'Impossible de supprimer : données associées');
        }
    }

    public function changeStatus(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'status' => ['required', 'string', 'in:' . Status::ACTIVE . ',' . Status::INACTIVE],
            ]);

            $user->update([
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::SUCCESS, Status::message(Status::UPDATED, 'Statut'));
        } catch (\Exception $e) {
            Log::error('Erreur changement statut user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }
}
