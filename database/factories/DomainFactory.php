<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'domain' => $this->faker->unique()->word(),
            'tenant_id' => \App\Models\Tenant::factory(),
            'is_primary' => $this->faker->randomElement([0, 1]),
            'is_fallback' => $this->faker->randomElement([0, 1]),
        ];
    }
}