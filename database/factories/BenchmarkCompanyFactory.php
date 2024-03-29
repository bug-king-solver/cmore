<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\BenchmarkCompany>
 */
class BenchmarkCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'enabled' => $this->faker->randomElement([0, 1]),
            'note' => $this->faker->text(30),
            'business_sector_id' => \App\Models\BusinessSector::factory(),
            'name' => $this->faker->name(),
            'ticker' => $this->faker->text(),
            'headquarters' => $this->faker->country(),
        ];
    }
}
