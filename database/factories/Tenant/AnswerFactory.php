<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'questionnaire_id' => \App\Models\Tenant\Questionnaire::factory(),
            'question_id' => \App\Models\Tenant\Question::factory(),
        ];
    }
}
