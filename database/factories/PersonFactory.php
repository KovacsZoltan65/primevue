<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        $gender = fake()->randomFloat(null, 0, 1) == 1 ? 'male' : 'female';
        
        return [
            'name' => fake()->name($gender), 
            'email' => fake()->email(), 
            'password' => fake()->password(6, 8), 
            'language' => 'hu', 
            'birthdate' => fake()->date('Y-m-d', '-25 year'),
            // 'birthdate' => fake()->dateTimeBetween('1960-01-01', '2000-12-31')
            'active' => 1
        ];
    }
}
