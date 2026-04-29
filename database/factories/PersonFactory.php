<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'last_name'             => $this->faker->lastName(),
            'first_name'            => $this->faker->firstName(),
            'phone'                 => $this->faker->unique()->phoneNumber(),
            'phone_code'            => $this->faker->numberBetween(1, 999),
            'gender'                => $this->faker->randomElement(['male', 'female']),
            'country_id'            => $this->faker->randomElement(Country::pluck('id')->toArray()),
        ];
    }
}