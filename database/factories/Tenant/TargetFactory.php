<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Target;
use Illuminate\Database\Eloquent\Factories\Factory;

class TargetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Target::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startDate = carbon()->now()->subDays(rand(1, 7))->format('Y-m-d');
        $dueDate = carbon()->parse($startDate)->addDays(rand(1, 7))->format('Y-m-d');

        $status = $this->faker->randomElement(['ongoing', 'completed']);
        $completedAt = null;
        $startedAt = null;

        if ($status == 'ongoing' || $status == 'completed') {
            $startedAt = $this->faker->dateTimeBetween($startDate, $dueDate);
        }
        if ($status == 'completed') {
            $completedAt = $this->faker->dateTimeBetween($startDate, $dueDate);
        }

        $createdAt = carbon()->parse($startDate)->subDays(rand(1, 7))->format('Y-m-d');

        $indicators = Indicator::all()->pluck('id')->toArray();
        $companies = Company::all()->pluck('id')->toArray();
        $users = \App\Models\User::all()->pluck('id')->toArray();

        return [
            'company_id' => $this->faker->randomElement($companies),
            'user_id' => $this->faker->randomElement($users),
            'indicator_id' => $this->faker->randomElement($indicators),
            'title' => $this->faker->name,
            'description' => $this->faker->text(150),
            'goal' => $this->faker->text(15),
            'our_reference' => $this->faker->text(15),
            'status' => $status,
            'start_date' => $startDate,
            'due_date' => $dueDate,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
