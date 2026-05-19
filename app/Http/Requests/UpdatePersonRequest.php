<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $person = $this->route('person');

        return [
            'country_id' => ['nullable', 'exists:countries,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'phone_code' => ['nullable', 'string', 'max:7'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', Rule::unique('people', 'email')->ignore($person)],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'gender.in' => 'Le genre doit être masculin ou féminin.',
            'country_id.exists' => 'Le pays sélectionné n\'existe pas.',
            'email.unique' => 'Cet email est déjà utilisé par une autre personne.',
        ];
    }

    public function attributes(): array
    {
        return [
            'country_id' => 'pays',
            'first_name' => 'prénom',
            'last_name' => 'nom',
            'gender' => 'genre',
            'phone_code' => 'indicatif téléphonique',
            'phone' => 'téléphone',
            'email' => 'adresse email',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^0-9]/', '', $this->phone),
            ]);
        }
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $person = $this->route('person');
            
            if ($this->filled('phone') && $this->filled('phone_code')) {
                $exists = \App\Models\Person::where('phone', $this->phone)
                    ->where('phone_code', $this->phone_code)
                    ->when($person, fn($q) => $q->where('id', '!=', $person->id))
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('phone', 'Ce numéro de téléphone est déjà utilisé avec cet indicatif.');
                }
            }
        });
    }
}