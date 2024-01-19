<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;
use App\Models\Tenant\QuestionOptions\Matrix;
use App\Models\Tenant\QuestionOptions\Simple;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuestionOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $commentable = $this->commentable();

        return [
            'enabled' => $this->faker->randomElement([0, 1]),
            'note' => $this->faker->text(50),
            'comment_required' => $this->faker->randomElement([0, 1]),
            'attachment_required' => $this->faker->randomElement([0, 1]),
            'question_id' => Question::factory(),
            'question_option_id' => $commentable::factory(),
            'question_option_type' => $commentable,
            'order' =>  $this->faker->randomDigit(),
        ];
    }

    public function commentable()
    {
        return $this->faker->randomElement([
            Simple::class,
            Matrix::class,
        ]);
    }
}
