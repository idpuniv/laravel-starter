<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'person_id' => ['required', 'exists:people,id'],

            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],

            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],

            'password' => ['required', 'confirmed', Password::min(8)],

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
            'email.required' => 'L’email est obligatoire.',
            'email.email' => 'Format email invalide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'username.unique' => 'Ce nom d’utilisateur est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }
}