<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
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
}
