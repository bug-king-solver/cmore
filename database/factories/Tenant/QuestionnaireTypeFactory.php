<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\QuestionnaireType;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionnaireTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuestionnaireType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'enabled' => $this->faker->randomElement([0, 1]),
            'name' => $this->faker->randomElement(['Assess', 'Screen', 'BCSD']),
            'note' => $this->faker->text(30),

        ];
    }
}
