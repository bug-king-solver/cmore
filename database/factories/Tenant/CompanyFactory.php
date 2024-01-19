<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Symfony\Component\Intl\Countries;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Company::class;

    public function definition()
    {
        $countries = Countries::getAlpha3Codes();

        return [
            'business_sector_id' => $this->faker->numberBetween(1, 13),
            'name' => $this->faker->company(),
            'country' => $this->faker->randomElement($countries),
            'vat_country' => $this->faker->randomElement($countries),
            'vat_number' => $this->faker->randomNumber(),
            'founded_at' => $this->faker->date(),
        ];
    }
}
