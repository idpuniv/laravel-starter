<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [

            'person_id' => ['required', 'exists:people,id'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId),
            ],

            'password' => ['nullable', 'confirmed', Password::min(8)],

            'status' => ['nullable', 'string'],

            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],

            'teams' => ['nullable', 'array'],
            'teams.*' => ['exists:teams,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cet email est déjà utilisé.',
            'username.unique' => 'Ce nom d’utilisateur est déjà utilisé.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }
}