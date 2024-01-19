<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\BenchmarkData>
 */
class BenchmarkDataFactory extends Factory
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
            'reporter_id' => \App\Models\Admin::factory(),
            'validator_id' => \App\Models\Admin::factory(),
            'indicator_id' => \App\Models\Indicator::factory(),
            'value' => $this->faker->text(),
            'value_usd' => $this->faker->randomFloat(2, 10, 100),
            'value_eur' => $this->faker->randomFloat(2, 10, 100),
            'validated_at' => $this->faker->date(),
        ];
    }
}
