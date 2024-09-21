<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProcessFlow>
 */
class ProcessFlowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->subDays($this->faker->numberBetween(1, 365));

        $week = $startDate->format('W');
        $weekString = "Week $week";
        return [
            'name' => $this->faker->word,
            'start_step_id' => 0,
            'frequency' => $this->faker->randomElement(['daily', 'weekly', 'hourly', 'monthly', 'yearly', 'none']),
            'status' => $this->faker->boolean,
            'frequency_for' => $this->faker->randomElement(['users', 'customers', 'suppliers', 'contractors', 'none']),
            // 'day'           => $this->faker->word,
            // 'week'          => $this->faker->word,
            // 'day'           => $startDate->dayOfWeek,
            'day' => $this->faker->dayOfWeek,
            'week' => $weekString,
            "start_user_designation" => 1,
            "start_user_department" => 1,
            "start_user_unit" => 1,

        ];
    }
}
