<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Source::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'name' => [
                'en' => $this->faker->name,
                'pt-PT' => $this->faker->name,
                'pt-BR' => $this->faker->name,
                'es' => $this->faker->name,
                'fr' => $this->faker->name,
            ],

        ];
    }
}
