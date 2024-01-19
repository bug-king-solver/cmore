<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Company;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionnaireType;
use App\Nova\Tenant\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionnaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Questionnaire::class;

    public function definition()
    {
        return [

            'questionnaire_type_id' => QuestionnaireType::factory(),
            'created_by_user_id' => \App\Models\User::factory(),
            'company_id' => Company::factory(),
            'is_ready' =>  $this->faker->randomElement([0, 1]),
            'progress' => $this->faker->randomDigit(),
            'from' => $this->faker->date(),
            'to' => $this->faker->date(),
            'countries' => [$this->faker->countryCode],
            //            'categories' => Category::factory(),
            //            'questions' => Question::factory(),
            'completed_at' => $this->faker->date(),
            'submitted_at' => $this->faker->date(),
        ];
    }
}
