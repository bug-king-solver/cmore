<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\BusinessSector;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessSectorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusinessSector::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //            'parent_id' => BusinessSector::factory(),
            'enabled' => $this->faker->randomElement([0, 1]),
            'note' => $this->faker->text(30),
            'name' => [
                'en' => $this->faker->name,
                'pt-PT' => $this->faker->name,
                'pt-BR' => $this->faker->name,
                'es' => $this->faker->name,
                'fr' => $this->faker->name,
            ],

        ];
    }
}
