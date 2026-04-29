<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Person (toujours requis)
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'phone_code' => 'nullable|string|max:10',
            'country_id' => 'nullable|exists:countries,id',
            'gender' => 'nullable|in:male,female',
            
            // Optionnel : créer un compte pour une personne existante
            'person_id' => 'nullable|exists:persons,id',
            
            // User (requis UNIQUEMENT si on crée un compte)
            'email' => 'required_if:person_id,!=,null|required_if:create_account,1|nullable|email|unique:users,email',
            'username' => 'nullable|string|max:255|unique:users,username',
            'password' => 'required_if:person_id,!=,null|required_if:create_account,1|nullable|string|min:6|confirmed',
            'status' => 'nullable|in:active,inactive,banned',
            'team_id' => 'nullable|exists:teams,id',
            
            // Rôles
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            
            // Flag
            'create_account' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required_if' => 'L\'email est obligatoire pour créer un compte.',
            'password.required_if' => 'Le mot de passe est obligatoire pour créer un compte.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}