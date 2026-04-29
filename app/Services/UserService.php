<?php

namespace App\Services;

use App\Models\User;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Créer une personne (avec ou sans compte utilisateur)
     */
    public function create(array $data): Person
    {
        return DB::transaction(function () use ($data) {
            // Créer la personne
            $person = Person::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
                'phone_code' => $data['phone_code'] ?? null,
                'country_id' => $data['country_id'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);

            // Si email et password sont fournis, créer aussi l'utilisateur
            if (isset($data['email']) && isset($data['password'])) {
                User::create([
                    'email' => $data['email'],
                    'username' => $data['username'] ?? null,
                    'password' => Hash::make($data['password']),
                    'status' => $data['status'] ?? 'active',
                    'team_id' => $data['team_id'] ?? 1,
                    'person_id' => $person->id,
                ]);
                
                $person->load('user');
            }

            return $person;
        });
    }

    /**
     * Ajouter un compte à une personne existante
     */
    public function addUserToPerson(string $personId, array $data): User
    {
        return DB::transaction(function () use ($personId, $data) {
            $person = Person::findOrFail($personId);
            
            if ($person->user) {
                throw new \Exception('Cette personne a déjà un compte utilisateur.');
            }
            
            return User::create([
                'email' => $data['email'],
                'username' => $data['username'] ?? null,
                'password' => Hash::make($data['password']),
                'status' => $data['status'] ?? 'active',
                'team_id' => $data['team_id'] ?? 1,
                'person_id' => $person->id,
            ]);
        });
    }

    /**
     * Mettre à jour une personne et son compte utilisateur
     */
    public function update(string $personId, array $data): Person
    {
        return DB::transaction(function () use ($personId, $data) {
            $person = Person::findOrFail($personId);
            
            // Mettre à jour la personne
            $person->update([
                'first_name' => $data['first_name'] ?? $person->first_name,
                'last_name' => $data['last_name'] ?? $person->last_name,
                'phone' => $data['phone'] ?? $person->phone,
                'phone_code' => $data['phone_code'] ?? $person->phone_code,
                'country_id' => $data['country_id'] ?? $person->country_id,
                'gender' => $data['gender'] ?? $person->gender,
            ]);
            
            // Si l'utilisateur existe et qu'on a des données utilisateur
            if ($person->user && isset($data['email'])) {
                $userData = [
                    'email' => $data['email'],
                    'username' => $data['username'] ?? $person->user->username,
                    'status' => $data['status'] ?? $person->user->status,
                    'team_id' => $data['team_id'] ?? $person->user->team_id,
                ];
                
                if (!empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }
                
                $person->user->update($userData);
            }
            
            return $person->load('user');
        });
    }

    /**
     * Récupérer une personne avec son utilisateur
     */
    public function find(string $personId): Person
    {
        return Person::with('user.roles', 'user.team', 'country')
            ->findOrFail($personId);
    }

    /**
     * Supprimer une personne et son compte utilisateur
     */
    public function delete(string $personId): void
    {
        DB::transaction(function () use ($personId) {
            $person = Person::findOrFail($personId);
            
            if ($person->user) {
                $person->user->delete();
            }
            
            $person->delete();
        });
    }

    /**
     * Supprimer uniquement le compte utilisateur
     */
    public function deleteUserOnly(string $personId): void
    {
        DB::transaction(function () use ($personId) {
            $person = Person::findOrFail($personId);
            
            if ($person->user) {
                $person->user->delete();
            }
        });
    }

    /**
     * Liste des personnes
     */
    public function getAll(array $filters = [])
    {
        $query = Person::with('user');
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }
        
        if (isset($filters['has_account'])) {
            if ($filters['has_account']) {
                $query->has('user');
            } else {
                $query->doesntHave('user');
            }
        }
        
        return $query->paginate($filters['per_page'] ?? 25);
    }
}