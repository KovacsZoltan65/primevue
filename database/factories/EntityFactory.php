<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entity>
 */
class EntityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        $faker = Factory::create();
        
        $company_ids = NULL;
        $persons_ids = NULL;
        
        return [
            'name' => '',
            'email' => fake()->email(),
            'companies_id' => '',
            'persons_id' => '',
            'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
            'end_date' => $faker->dateTimeBetween('now', '+10 year'),
            'last_export' => NULL,
            'active' => 1,
        ];
    }
}
