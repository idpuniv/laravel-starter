<?php

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        $rules = [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'gender' => ['nullable', 'in:male,female'],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'username' => [
                'nullable',
                'string',
                Rule::unique('users', 'username')->ignore($userId),
            ],

            'status' => [
                'required',
                Rule::in([
                    Status::ACTIVE,
                    Status::INACTIVE,
                ]),
            ],
        ];

        if (config('permission.teams', false)) {
            $rules['team_id'] = ['nullable', 'exists:teams,id'];
        }

        return $rules;
    }
}