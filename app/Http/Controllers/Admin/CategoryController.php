<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Flash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index()
    {
        return view('admin.categories.index');
    }

    public function create()
    {
        try {
            return view('admin.categories.create', [
                'categories' => $this->parents(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

            $category = Category::create($data);

            return redirect()
                ->route('admin.categories.show', $category->id)
                ->with(Flash::SUCCESS, __('messages.created'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.create_failed'));
        }
    }

    public function show(Category $category)
    {
        try {
            return view('admin.categories.show', [
                'category' => $category->load(['parent', 'children']),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.categories.index')
                ->with(Flash::ERROR, __('messages.not_found'));
        }
    }

    public function edit(Category $category)
    {
        try {
            return view('admin.categories.edit', [
                'category' => $category,
                'categories' => $this->parents($category->id),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.categories.index')
                ->with(Flash::ERROR, __('messages.operation_failed'));
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $data = $request->validated();
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

            $category->update($data);

            return redirect()
                ->route('admin.categories.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.update_failed'));
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()
                ->route('admin.categories.index')
                ->with(Flash::SUCCESS, __('messages.deleted'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.categories.index')
                ->with(Flash::ERROR, __('messages.delete_failed'));
        }
    }

    /**
     * Liste des catégories pouvant servir de parent
     * (exclut la catégorie courante en édition).
     */
    private function parents(?int $exclude = null)
    {
        return Category::when($exclude, fn ($q) => $q->where('id', '!=', $exclude))
            ->orderBy('name')
            ->get();
    }
}
