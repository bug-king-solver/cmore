<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Initiative;
use Illuminate\Database\Eloquent\Factories\Factory;

class InitiativeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Initiative::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //            'parent_id' => \App\Models\Tenant\Initiative::factory(),
            'category_id' => \App\Models\Tenant\Category::factory(),
            'sdg_id' => \App\Models\Tenant\Sdg::factory(),
            'name' => [
                'en' => $this->faker->text(10),
                'pt-PT' => $this->faker->text(10),
                'pt-BR' => $this->faker->text(10),
                'es' => $this->faker->text(10),
                'fr' => $this->faker->text(10),
            ],
            'order' =>  $this->faker->randomDigit(),
            'enabled' => $this->faker->randomElement([0, 1]),
        ];
    }
}
