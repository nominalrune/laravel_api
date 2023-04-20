<?php

namespace Tests\Feature\Calendar;

use App\Models\CalendarEvent;
use Illuminate\Support\Carbon;
use Tests\ApiTestCase;

class IndexTest extends ApiTestCase
{
    /**
     * @test
     */
    public function can_not_access_calendar_without_login(): void
    {
        $this->get('/api/calendar')->assertStatus(401);
    }

    /**
     * @test
     */
    public function can_access_calendar_with_login(): void
    {
        $this->login()->get('/api/calendar')->assertStatus(200);
    }

    public function return_calendar_events_according_to_url_query(): void
    {
        $this->travelTo(Carbon::create(2021, 1, 1, 0, 0, 0));
        CalendarEvent::factory()->create([
            'title' => 'title is a title',
            'description' => 'description is a description',
            'start_at' => '2021-01-01 00:00:00',
            'end_at' => '2021-01-01 01:00:00',
            'user_id' => $this->user->id,
        ]);
        $query = [
            'start_at' => '2021-01-01 00:00:00',
            'end_at' => '2021-01-01 01:00:00',
        ];
        $this->login()->get('/api/calendar')->assertJson([
            'calendar_events' => [
                [
                    'id' => 1,
                    'title' => 'title is a title',
                    'description' => 'description is a description',
                    'start_at' => '2021-01-01 00:00:00',
                    'end_at' => '2021-01-01 01:00:00',
                    'user_id' => $this->user->id,
                ],
            ],
        ]);
    }
}
