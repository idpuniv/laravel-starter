<?php

namespace App\Services;

use App\Models\User;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $person = Person::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'phone' => $data['phone'] ?? null,
                'phone_code' => $data['phone_code'] ?? null,
                'country_id' => $data['country_id'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);

            return User::create([
                'email' => $data['email'],
                'username' => $data['username'] ?? null,
                'password' => bcrypt($data['password']),
                'status' => $data['status'] ?? null,
                'team_id' => $data['team_id'] ?? null,
                'person_id' => $person->id,
            ]);
        });
    }

    public function update(string $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {

            $user = User::findOrFail($id);

            $user->update([
                'email' => $data['email'],
                'username' => $data['username'] ?? null,
                'status' => $data['status'],
                'team_id' => $data['team_id'] ?? null,
            ]);

            if (isset($data['nom']) || isset($data['prenom'])) {
                $user->person?->update([
                    'nom' => $data['nom'] ?? $user->person->nom,
                    'prenom' => $data['prenom'] ?? $user->person->prenom,
                    'phone' => $data['phone'] ?? $user->person->phone,
                    'phone_code' => $data['phone_code'] ?? $user->person->phone_code,
                    'country_id' => $data['country_id'] ?? $user->person->country_id,
                    'gender' => $data['gender'] ?? $user->person->gender,
                ]);
            }

            return $user;
        });
    }

    public function find(string $id): User
    {
        return User::with(['roles', 'permissions', 'team', 'person'])
            ->findOrFail($id);
    }

    public function delete(string $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
