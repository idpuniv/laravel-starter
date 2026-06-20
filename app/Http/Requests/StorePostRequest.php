<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'category_id'   => ['nullable', 'integer', 'exists:categories,id'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'summary'      => ['nullable', 'string', 'max:500'],
            'content'      => ['required', 'string'],
            'status'       => ['nullable', Rule::enum(PostStatus::class)],
            'published_at' => ['nullable', 'date'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['integer', 'exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'     => 'Le titre est obligatoire.',
            'content.required'   => 'Le contenu est obligatoire.',
            'slug.unique'        => 'Ce slug est déjà utilisé.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'tags.*.exists'      => 'Une des étiquettes sélectionnées n\'existe pas.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'        => 'titre',
            'slug'         => 'slug',
            'category_id'  => 'catégorie',
            'summary'      => 'résumé',
            'content'      => 'contenu',
            'status'       => 'statut',
            'published_at' => 'date de publication',
            'tags'         => 'étiquettes',
        ];
    }
}
