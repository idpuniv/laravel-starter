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
        // Crée une Person d'abord car person_id est NOT NULL
        $person = PersonFactory::new()->create();
        
        return [
            'username' => $this->faker->boolean(70) ? $this->faker->unique()->userName() : null,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'team_id' => null,
            'status' => 'active',
            'person_id' => $person->id,
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

    /**
     * Create a viewer user.
     */
    public function viewer(): static
    {
        return $this->state(fn(array $attributes) => [
            'username' => 'viewer_' . Str::random(5),
        ])->withRole('viewer');
    }

    /**
     * Set user as active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Set user as inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Set user as banned.
     */
    public function banned(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'banned',
        ]);
    }

    /**
     * Create a user without username.
     */
    public function withoutUsername(): static
    {
        return $this->state(fn(array $attributes) => [
            'username' => null,
        ]);
    }

    /**
     * Create a user with a specific username.
     */
    public function withUsername(string $username): static
    {
        return $this->state(fn(array $attributes) => [
            'username' => $username,
        ]);
    }

    /**
     * Create a user with custom person attributes.
     */
    public function withPerson(array $personAttributes = []): static
    {
        return $this->state(function (array $attributes) use ($personAttributes) {
            $person = PersonFactory::new()->create($personAttributes);
            return [
                'person_id' => $person->id,
            ];
        });
    }
}