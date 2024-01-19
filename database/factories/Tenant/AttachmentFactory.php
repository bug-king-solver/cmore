<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Attachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

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
