<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Country;
use App\Services\UserService;
use App\Support\Flash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
        $this->authorizeResource(User::class, 'user');
    }

    private function isTeamsEnabled(): bool
    {
        return Config::get('permission.teams', false);
    }

    public function index()
    {
        return redirect()->route('admin.people.index');
    }

    public function create()
    {
        try {
            $roles = Role::all();

            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.create', compact('roles', 'teams'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $this->userService->create($request->validated());

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::SUCCESS, __('messages.created'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.create_failed'));
        }
    }

    public function show(User $user)
    {
        try {
            return view('admin.users.show', compact('user'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::ERROR, __('messages.not_found'));
        }
    }

    public function edit(User $user)
    {
        try {
            $countries = Country::all();

            $teams = $this->isTeamsEnabled()
                ? Team::where('status', Status::ACTIVE)->orderBy('name')->get()
                : collect();

            return view('admin.users.edit', compact('user', 'countries', 'teams'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::ERROR, __('messages.operation_failed'));
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $this->userService->update($user->id, $request->validated());

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.update_failed'));
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->delete($user->id);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::SUCCESS, __('messages.deleted'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::ERROR, __('messages.delete_failed'));
        }
    }

    public function restore(User $user)
    {
        try {
            $this->userService->restore($user->id);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function forceDestroy(User $user)
    {
        try {
            $this->userService->forceDelete($user->id);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::SUCCESS, __('messages.deleted'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::ERROR, __('messages.operation_failed'));
        }
    }

    public function changeStatus(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'status' => [
                    'required',
                    Rule::in([Status::ACTIVE, Status::INACTIVE])
                ],
            ]);

            $user->update([
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.users.index')
                ->with(Flash::ERROR, __('messages.operation_failed'));
        }
    }
}