<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function all()
    {
        return Category::with('parent')->latest()->paginate(15);
    }

    public function parents($excludeId = null)
    {
        return Category::when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('name')
            ->get();
    }

    public function find($id)
    {
        return Category::find($id);
    }

    public function create(array $data)
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $category->update($data);
        return $category;
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}