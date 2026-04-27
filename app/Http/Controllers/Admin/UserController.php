<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\UsersDataTable;
use App\Models\User;
use App\Models\Country;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Enums\Status;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    private function isTeamsEnabled(): bool
    {
        return Config::get('permission.teams', false);
    }

    public function index(Request $request)
    {
        return UsersDataTable::make($request)->render();
    }

    public function create()
    {
        try {
            $countries = Country::all();
            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.create', compact('teams', 'countries'));
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
                ->with(Status::ERROR, Status::message(Status::ERROR, 'Utilisateur'));
        }
    }

    public function edit(string $id)
    {
        try {
            $user = $this->userService->find($id);

            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.edit', compact('user', 'teams'));
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
            return redirect()
                ->route('admin.users.index')
                ->with(Status::ERROR, Status::message(Status::ERROR));
        }
    }
}