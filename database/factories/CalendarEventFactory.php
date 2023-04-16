<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CalendarEventFactory extends Factory
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
            'start_at' => $this->faker->dateTimeBetween('now', '+1 hour'),
            'end_at' => $this->faker->dateTimeBetween('+1 hour', '+2 hours'),
            // 'user_id' => \App\Models\User::factory(),
        ];
    }
}
