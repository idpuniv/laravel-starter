<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use App\Models\Admin;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'nom'             => $this->faker->lastName(),
            'prenom'          => $this->faker->firstName(),
            'phone'           => $this->faker->unique()->phoneNumber(),
            'phone_code'      => $this->faker->numberBetween(1, 999),
            'gender'          => $this->faker->randomElement(['male', 'female']),
            'country_id'      => $this->fake()->randomElement(Country::pluck('id')->toArray()),
            'personable_id'   => null,
            'personable_type' => null,
        ];
    }

    /**
     * Associer à un User
     */
    public function forUser(): static
    {
        return $this->state(fn () => [
            'personable_id'   => User::factory(),
            'personable_type' => User::class,
        ]);
    }

    /**
     * Associer à un Admin
     */
    public function forAdmin(): static
    {
        return $this->state(fn () => [
            'personable_id'   => Admin::factory(),
            'personable_type' => Admin::class,
        ]);
    }
}
