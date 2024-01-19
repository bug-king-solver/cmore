<?php

namespace Database\Factories\Tenant\QuestionOptions;

use App\Models\Tenant\QuestionOptions\Simple;
use Illuminate\Database\Eloquent\Factories\Factory;

class SimpleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Simple::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'label' => [
                'en' =>    $this->faker->name,
                'pt-PT' => $this->faker->name,
                'pt-BR' => $this->faker->name,
                'es' =>    $this->faker->name,
                'fr' =>    $this->faker->name,
            ],
            'value' => $this->faker->name,

        ];
    }
}
