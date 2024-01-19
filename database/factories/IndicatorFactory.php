<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Indicator>
 */
class IndicatorFactory extends Factory
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
            //            'unit_qty' => \App\Models\Indicator::factory(),
            'unit_default' => $this->faker->randomFloat(2, 10, 100),
            'calc' => $this->faker->text(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'has_benchmarking' => $this->faker->randomElement([0, 1]),
        ];
    }
}
