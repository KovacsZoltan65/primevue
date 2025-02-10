<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShiftType>
 */
class ShiftTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => '',
            'code' => '',
            'name' => '',
            'trunk_time_start' => '',
            'trunk_time_end' => '',
            'edge_time_start' => '',
            'edge_time_end' => '',
            'active' => '',
        ];
    }
}
