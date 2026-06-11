<?php

namespace App\Http\Controllers;

use App\Support\Flash;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Country;
use App\Models\Person;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class PersonController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
        $this->authorizeResource(Person::class, 'person');
    }

    public function index()
    {
        return view('admin.people.index');
    }

    public function create()
    {
        try {
            return view('admin.people.create', [
                'countries' => $this->countries(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function store(StorePersonRequest $request)
    {
        try {
            $person = Person::create($request->validated());

            return redirect()
                ->route('admin.people.show', $person->id)
                ->with(Flash::SUCCESS, __('messages.created'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.create_failed'));
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
                ->with(Flash::ERROR, __('messages.not_found'));
        }
    }

    public function edit(Person $person)
    {
        try {
            return view('admin.people.edit', [
                'person' => $person->load('country'),
                'countries' => $this->countries(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.people.index')
                ->with(Flash::ERROR, __('messages.operation_failed'));
        }
    }

    public function update(UpdatePersonRequest $request, Person $person)
    {
        try {
            $person->update($request->validated());

            return redirect()
                ->route('admin.people.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.update_failed'));
        }
    }

    public function showAddUserForm(Person $person)
    {
        try {
            if ($person->user) {
                return back()->with(
                    Flash::ERROR,
                    __('messages.person.already_has_account')
                );
            }

            return view('admin.users.create', compact('person'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function addUser(StoreUserRequest $request, Person $person)
    {
        try {
            if ($person->user) {
                return back()->with(
                    Flash::ERROR,
                    __('messages.person.already_has_account')
                );
            }

            $this->userService->create([
                ...$request->validated(),
                'person_id' => $person->id,
            ]);

            return redirect()
                ->route('admin.people.show', $person->id)
                ->with(Flash::SUCCESS, __('messages.created'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.create_failed'));
        }
    }

    public function destroy(Person $person)
    {
        try {
            $person->user?->delete();
            $person->delete();

            return redirect()
                ->route('admin.people.index')
                ->with(Flash::SUCCESS, __('messages.deleted'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.people.index')
                ->with(Flash::ERROR, __('messages.delete_failed'));
        }
    }

    private function countries()
    {
        return Country::orderBy('name')->get();
    }
}