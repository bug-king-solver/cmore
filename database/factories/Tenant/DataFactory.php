<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Data;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Data::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $companies = \App\Models\Tenant\Company::inRandomOrder()->first()->id;
        $indicator = \App\Models\Tenant\Indicator::inRandomOrder()->first()->id;
        $questionnaire = \App\Models\Tenant\Questionnaire::inRandomOrder()->first()->id;

        return [
            'company_id' => $companies,
            'indicator_id' => $indicator,
            'questionnaire_id' => $questionnaire,
            'value' => $this->faker->randomFloat(2, 2, 10),
            'reported_at' => $this->faker->date,
        ];
    }
}
