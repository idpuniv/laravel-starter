<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Module;
use App\Support\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Permission::class, 'permission');
    }

    public function index()
    {
        try {
            $permissions = Permission::with('module')
                ->orderBy('module_id')
                ->paginate(10);

            return view('admin.permissions.index', compact('permissions'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function show(Permission $permission)
    {
        try {
            $permission->load('module');

            return view('admin.permissions.show', compact('permission'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.permissions.index')
                ->with(
                    Flash::ERROR,
                    __('messages.not_found')
                );
        }
    }

    public function edit(Permission $permission)
    {
        try {
            $modules = Module::where('is_active', true)->get();

            return view('admin.permissions.edit', compact(
                'permission',
                'modules'
            ));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.permissions.index')
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }

    public function update(Request $request, Permission $permission)
    {
        try {
            $validated = $request->validate([
                'label' => ['nullable', 'string', 'max:255'],
                'module_id' => ['nullable', 'exists:modules,id'],
            ]);

            $module = $validated['module_id']
                ? Module::find($validated['module_id'])
                : null;

            $permission->update([
                'label' => $validated['label'] ?? $permission->label,
                'module_id' => $validated['module_id'],
                'module_slug' => $module?->slug,
            ]);

            return redirect()
                ->route('admin.permissions.index')
                ->with(
                    Flash::SUCCESS,
                    __('messages.updated')
                );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(
                    Flash::ERROR,
                    __('messages.operation_failed')
                );
        }
    }
}