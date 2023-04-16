<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
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
            // 'recordable_type' => $this->faker->randomElement([\App\Models\Task::factory()]),
            // 'recordable_id' => $this->faker->randomElement([\App\Models\Task::factory()]),
            'date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'time' => $this->faker->randomNumber(2),
            // 'user_id' => \App\Models\User::factory(),
        ];
    }
}
