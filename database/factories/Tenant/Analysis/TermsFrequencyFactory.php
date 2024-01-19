<?php

namespace Database\Factories\Tenant\Analysis;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermsFrequencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->date();
        $dateDT = new DateTimeImmutable($date);

        return [
            'term' => $this->faker->word(),
            'weight' => $this->faker->numberBetween(0, 100),
            'year' => $dateDT->format('Y'),
            'month' => $dateDT->format('m'),
            'day' => $dateDT->format('d'),
            'week_year' => $dateDT->format('W'),
            'day_week' => $dateDT->format('N'),
        ];
    }
}
