<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Enums\Status;
use App\Models\Permission;
use App\Models\Module;

class PermissionController extends Controller
{



   public function __construct(
    ) {

        $this->authorizeResource(Permission::class, 'permission');
    }
    /**
     * Liste des permissions
     */
    public function index()
    {
        try {
            $permissions = Permission::with('module')
                ->orderBy('module_id')
                ->paginate(10);

            return view('admin.permissions.index', compact('permissions'));

        } catch (\Exception $e) {
            return back()->with(
                Status::ERROR,
                Status::message(Status::ERROR)
            );
        }
    }

    /**
     * Détail permission
     */
    public function show(string $id)
    {
        try {
            $permission = Permission::with('module')->findOrFail($id);

            return view('admin.permissions.show', compact('permission'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.permissions.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR, 'Permission')
                );
        }
    }

    /**
     * Formulaire édition
     */
    public function edit(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            $modules = Module::where('is_active', true)->get();

            return view('admin.permissions.edit', compact('permission', 'modules'));

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.permissions.index')
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                );
        }
    }

    /**
     * Mise à jour permission
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'label' => 'nullable|string|max:255',
                'module_id' => 'nullable|exists:modules,id'
            ]);

            $permission = Permission::findOrFail($id);

            $module = isset($validated['module_id'])
                ? Module::find($validated['module_id'])
                : null;

            $permission->update([
                'label' => $validated['label'] ?? $permission->label,
                'module_id' => $validated['module_id'],
                'module_slug' => $module?->slug
            ]);

            return redirect()
                ->route('admin.permissions.index')
                ->with(
                    Status::SUCCESS,
                    Status::message(Status::UPDATED, 'Permission')
                );

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(
                    Status::FAILED,
                    Status::message(Status::FAILED)
                );

        } catch (\Exception $e) {
            return back()
                ->with(
                    Status::ERROR,
                    Status::message(Status::ERROR)
                )
                ->withInput();
        }
    }
}