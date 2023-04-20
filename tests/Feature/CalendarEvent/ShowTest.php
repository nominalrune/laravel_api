<?php

namespace Tests\Feature\CalendarEvent;

use App\Models\CalendarEvent;
use Tests\ApiTestCase;

class ShowTest extends ApiTestCase
{
    private CalendarEvent $calendarEvent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calendarEvent = CalendarEvent::create([
            'title' => 'title is a title',
            'description' => 'description is a description',
            'start_at' => '2021-01-01 00:00:00',
            'end_at' => '2021-01-01 01:00:00',
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * @test
     */
    public function can_access_calendar_event_with_login(): void
    {
        $response = $this->login()->get('/api/calendar_events/'.$this->calendarEvent->id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $this->calendarEvent->id,
            'title' => $this->calendarEvent->title,
            'description' => $this->calendarEvent->description,
            'start_at' => $this->calendarEvent->start_at,
            'end_at' => $this->calendarEvent->end_at,
            'user_id' => $this->calendarEvent->user_id,
        ]);
    }

    /**
     * @test
     */
    public function cannot_access_calendar_event_without_login(): void
    {
        $response = $this->get('/api/calendar_events/'.$this->calendarEvent->id);
        $response->assertStatus(401);
    }
}
