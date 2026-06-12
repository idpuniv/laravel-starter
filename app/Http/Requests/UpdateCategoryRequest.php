<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category)],
            'description' => ['nullable', 'string'],
            'parent_id'   => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Le nom est obligatoire.',
            'slug.unique'      => 'Ce slug est déjà utilisé.',
            'parent_id.exists' => 'La catégorie parente sélectionnée n\'existe pas.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'nom',
            'slug'        => 'slug',
            'description' => 'description',
            'parent_id'   => 'catégorie parente',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $category = $this->route('category');

            // Une catégorie ne peut pas être sa propre parente
            if ($category && (int) $this->parent_id === (int) $category->id) {
                $validator->errors()->add('parent_id', 'Une catégorie ne peut pas être sa propre parente.');
            }
        });
    }
}
