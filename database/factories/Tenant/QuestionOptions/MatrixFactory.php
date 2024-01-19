<?php

namespace Database\Factories\Tenant\QuestionOptions;

use App\Models\Tenant\QuestionOptions\Matrix;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatrixFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Matrix::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'x' => [
                'en' => '{COUNTRIES}',
                'pt-PT' => '{COUNTRIES}',
                'pt-BR' => '{COUNTRIES}',
                'es' => '{COUNTRIES}',
                'fr' => '{COUNTRIES}',
            ],

            'y' => [
                'en' => $this->faker->text(150),
                'pt-PT' => $this->faker->text(150),
                'pt-BR' => $this->faker->text(150),
                'es' => $this->faker->text(150),
                'fr' => $this->faker->text(150),
            ],

        ];
    }
}
