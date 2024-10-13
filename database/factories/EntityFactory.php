<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entity>
 */
class EntityFactory extends Factory
{

    protected $model = \App\Models\Entity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        return [
            'name' => $this->faker->company, // vagy bármilyen releváns adat az 'Entity' modellhez
            'email' => $this->faker->unique()->safeEmail,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'last_export' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'active' => 1,
            'company_id' => Company::factory(), // Alapértelmezett esetben egy új céget hoz létre, ha nincs megadva.
        ];
    }
}
