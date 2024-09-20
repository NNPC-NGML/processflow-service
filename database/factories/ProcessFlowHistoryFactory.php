<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProcessFlowHistory>
 */
class ProcessFlowHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'task_id' => $this->faker->numberBetween(1, 10),
            'step_id' => $this->faker->numberBetween(1, 10),
            'process_flow_id' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->boolean,
            "for" => "customer",
            "for_id" => $this->faker->numberBetween(1, 10),
            "approval" => $this->faker->boolean,

        ];
    }
}
