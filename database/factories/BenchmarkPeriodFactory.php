<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\BenchmarkPeriod>
 */
class BenchmarkPeriodFactory extends Factory
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
            'name' => $this->faker->name,
            'from' => $this->faker->date,
            'to' => $this->faker->date,
        ];
    }
}
