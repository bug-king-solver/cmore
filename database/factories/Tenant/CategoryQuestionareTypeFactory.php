<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\CategoryQuestionnaireType;
use App\Models\Tenant\QuestionnaireType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryQuestionareTypeFactory extends Factory
{
    protected $model = CategoryQuestionnaireType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'questionnaire_type_id' => QuestionnaireType::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
