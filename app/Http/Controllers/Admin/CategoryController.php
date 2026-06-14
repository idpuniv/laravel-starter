<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Support\Flash;
use App\Support\Breadcrumb;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index()
    {
        Breadcrumb::add('Catégories', 'admin.categories.index');
        
        $categories = $this->categoryService->all();
        
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        try {
            Breadcrumb::add('Catégories', 'admin.categories.index')
                      ->add('Créer');

            return view('admin.categories.create', [
                'categories' => $this->categoryService->parents(),
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
            $category = $this->categoryService->create($request->validated());

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
            Breadcrumb::add('Catégories', 'admin.categories.index')
                      ->add($category->name);

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
            Breadcrumb::add('Catégories', 'admin.categories.index')
                      ->add($category->name, 'admin.categories.show', $category)
                      ->add('Modifier');

            return view('admin.categories.edit', [
                'category' => $category,
                'categories' => $this->categoryService->parents($category->id),
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
            $this->categoryService->update($category, $request->validated());

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
            $this->categoryService->delete($category);

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
}