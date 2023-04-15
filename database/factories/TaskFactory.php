<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type'=> $this->faker->randomElement(['task', 'event']),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'due' => $this->faker->dateTimeBetween('now', '+1 month'),
            // 'owner_id' => \App\Models\User::factory(),
            'status'=> $this->faker->randomNumber(1),
            // 'parent_task_id'=> \App\Models\Task::factory(),

        ];
    }
}
