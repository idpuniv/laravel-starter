<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\UsersDataTable;
use App\Models\Role;
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
        $this->authorizeResource(User::class, 'user');
    }

    private function isTeamsEnabled(): bool
    {
        return Config::get('permission.teams', false);
    }

    // public function index(Request $request)
    // {
    //     // return UsersDataTable::make($request)->render();
    //     return view('admin.users.index');
    // }

    public function create()
    {
        try {
            $roles = Role::all();

            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.create', compact('roles', 'teams'));
        } catch (\Exception $e) {
            return back()->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }

    public function store(StoreUserRequest $request)
    {
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

    public function show(string $id)
    {
        try {
            $user = $this->userService->find($id);

            return view('admin.users.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }

    public function edit(string $id)
    {
        try {
            $user = $this->userService->find($id);
            $countries = Country::all();

            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.edit', compact('user', 'countries', 'teams'));
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
            Log::error('Delete user error', [
                'user_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }

    /**
     * RESTORE USER
     */
    public function restore(string $id)
    {
        try {
            $this->userService->restore($id);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::SUCCESS, Status::message(Status::UPDATED, 'Utilisateur restauré'));
        } catch (\Exception $e) {
            Log::error('Restore user error', [
                'user_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }

    public function forceDestroy(string $id)
    {
        try {
            $this->userService->forceDelete($id);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::SUCCESS, 'Utilisateur supprimé définitivement');
        } catch (\Exception $e) {
            Log::error('Force delete user error', [
                'user_id' => $id,
                'error' => $e->getMessage(),
            ]);

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
            Log::error('Status change error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }
}