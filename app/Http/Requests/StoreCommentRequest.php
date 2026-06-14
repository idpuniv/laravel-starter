<?php

namespace App\Http\Requests;

use App\Enums\CommentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id'   => ['required', 'exists:posts,id'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'content'   => ['required', 'string', 'max:2000'],
            'status'    => ['nullable', Rule::enum(CommentStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.required'  => 'L\'article est obligatoire.',
            'post_id.exists'    => 'L\'article sélectionné n\'existe pas.',
            'parent_id.exists'  => 'Le commentaire parent n\'existe pas.',
            'content.required'  => 'Le contenu est obligatoire.',
        ];
    }

    public function attributes(): array
    {
        return [
            'post_id'   => 'article',
            'parent_id' => 'commentaire parent',
            'content'   => 'contenu',
            'status'    => 'statut',
        ];
    }
}
