<?php

namespace Database\Factories;

use App\Models\SubscriptionCancelation;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionCancelationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscriptionCancelation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tenant_id' => \App\Models\Tenant::factory(),
            'reason' => $this->faker->word(),
        ];
    }
}
