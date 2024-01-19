<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Indicator;
use Illuminate\Database\Eloquent\Factories\Factory;

class IndicatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Indicator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'category_id' => \App\Models\Tenant\Category::factory(),
            'name' => [
                'en' => $this->faker->text(10),
                'pt-PT' => $this->faker->text(10),
                'pt-BR' => $this->faker->text(10),
                'es' => $this->faker->text(10),
                'fr' => $this->faker->text(10),
            ],
            'description' => [
                'en' => $this->faker->text(100),
                'pt-PT' => $this->faker->text(100),
                'pt-BR' => $this->faker->text(100),
                'es' => $this->faker->text(100),
                'fr' => $this->faker->text(100),
            ],
            'note' => $this->faker->text(30),
            'enabled' => $this->faker->randomElement([0, 1]),
            'has_benchmarking' => $this->faker->randomElement([0, 1]),

        ];
    }
}
