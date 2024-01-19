<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //            'parent_id' => \App\Models\Tenant\Category::factory(),
            'enabled' => $this->faker->randomElement([0, 1]),
            'note' => $this->faker->text(30),
            'order' =>  $this->faker->randomDigit(),
            'name' => [
                'en' => $this->faker->name,
                'pt-PT' => $this->faker->name,
                'pt-BR' => $this->faker->name,
                'es' => $this->faker->name,
                'fr' => $this->faker->name,
            ],
            'description' => [
                'en' => $this->faker->text(100),
                'pt-PT' => $this->faker->text(100),
                'pt-BR' => $this->faker->text(100),
                'es' => $this->faker->text(100),
                'fr' => $this->faker->text(100),
            ],
        ];
    }
}
