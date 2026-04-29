<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Region>
 */
class RegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'ordering' => fake()->numberBetween(1,11),
            'latitude'=>fake()->text(),
            'longitude'=>fake()->text(),
            'created_by' => fake()->numberBetween(1,5),
            'updated_by' => fake()->numberBetween(1,5),
        ];
    }
}
