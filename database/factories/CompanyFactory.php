<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'directory' => fake()->word(),
            'registration_number' => fake()->phoneNumber(),
            'tax_id' => fake()->phoneNumber(),
            'country_id' => 92, 'city_id' =>  1,
            'address' => fake()->address()
        ];
    }
}
