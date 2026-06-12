<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($post)],
            'category_id'  => ['nullable', 'exists:categories,id'],
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
