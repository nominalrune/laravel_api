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
            'title' => 'title is a title',
            'description' => 'description is a description',
            'start_at' => new Carbon('2021-01-01T00:00'),
            'end_at' => new Carbon('2021-01-01T01:00'),
            'user_id' => 1,
        ];
    }
}
