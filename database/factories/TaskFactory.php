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
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'due' => $this->faker->dateTimeBetween('now', '+1 month'),
            'state' => 0,
            'subtasks' => [[
                'title' => 'subtask1',
                'state' => 0,
                'subtasks' => [],
            ], [
                'title' => 'subtask2',
                'state' => 0,
                'subtasks' => [],
            ]],

        ];
    }
}
