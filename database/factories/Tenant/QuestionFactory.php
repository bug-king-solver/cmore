<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionnaireType;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //            'parent_id' => \App\Models\Tenant\Question::factory(),
            'category_id' => \App\Models\Tenant\Category::factory(),
            'indicator_id' => \App\Models\Tenant\Indicator::factory(),
            'source_id' => \App\Models\Tenant\Source::factory(),
            'description' => [
                'en' => $this->faker->text(100),
                'pt-PT' => $this->faker->text(100),
                'pt-BR' => $this->faker->text(100),
                'es' => $this->faker->text(100),
                'fr' => $this->faker->text(100),
            ],
            'order' =>  $this->faker->randomDigit(),
            'enabled' => $this->faker->randomElement([0, 1]),
            'weight' => $this->faker->randomDigit(),
            'questionnaire_type_id' => QuestionnaireType::factory(),
            'answer_type' => $this->faker->randomElement([
                'binary', 'countries-multi', 'matrix', 'decimal', 'checkbox',
            ]),
        ];
    }
}
