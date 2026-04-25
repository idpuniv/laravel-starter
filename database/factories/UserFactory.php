<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Mot de passe par défaut sécurisé
            'team_id' => 1,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user has a specific team.
     */
    public function forTeam($team): static
    {
        return $this->state(fn(array $attributes) => [
            'team_id' => $team instanceof Team ? $team->id : $team,
        ]);
    }

    /**
     * Indicate that the user has a specific role (si vous utilisez spatie/laravel-permission).
     */
    public function withRole(string $role): static
    {
        return $this->afterCreating(function (User $user) use ($role) {
            $user->assignRole($role);
        });
    }

    /**
     * Create a user with a weak password (pour les environnements de test).
     */
    public function withWeakPassword(): static
    {
        return $this->state(fn(array $attributes) => [
            'password' => Hash::make('123456'),
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'username' => 'admin_' . Str::random(5),
            'email' => 'admin_' . Str::random(5) . '@example.com',
        ])->withRole('admin');
    }

    /**
     * Create a regular user.
     */
    public function user(): static
    {
        return $this->state(fn(array $attributes) => [
            'username' => 'member_' . Str::random(5),
        ])->withRole('user');
    }

    public function viewer(): static
    {
        return $this->state(fn(array $attributes) => [
            'username' => 'member_' . Str::random(5),
        ])->withRole('viewer');
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            // Logique supplémentaire après la création
            // Exemple : créer un profil utilisateur, etc.
        });
    }
}
