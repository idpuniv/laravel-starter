<?php

namespace App\Http\Requests;

use App\Enums\CommentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:2000'],
            'status'  => ['nullable', Rule::enum(CommentStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Le contenu est obligatoire.',
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => 'contenu',
            'status'  => 'statut',
        ];
    }
}
