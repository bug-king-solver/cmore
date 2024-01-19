<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\BenchmarkReport>
 */
class BenchmarkReportFactory extends Factory
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
            'period_id' => \App\Models\Admin::factory(),
            'company_id' => \App\Models\Admin::factory(),
            'currency' => $this->faker->currency(),
            'validated_at' => $this->faker->date(),
        ];
    }
}
