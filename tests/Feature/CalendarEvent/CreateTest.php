<?php

namespace Tests\Feature\CalendarEvent;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Tests\ApiTestCase;

class CreateTest extends ApiTestCase
{
    private array $attribute;
    protected function setUp(): void
    {
        parent::setUp();
        $this->attribute = [
            'title' => 'title is a title',
            'description' => 'description is a description',
            'start_at' => '2021-11-11T10:10:00.000+0900',
            'end_at' => '2021-11-11T11:10:00.000+0900',
            'user_id' => $this->user->id,
        ];
    }
    /**
     * @test
     */
    public function cannot_create_calendar_event_without_login(): void
    {
        $response = $this->post('/api/calendar_events', $this->attribute);
        $response->assertStatus(401);
    }
    /**
     * @test
     */
    public function can_create_calendar_event_with_login(): void
    {
        $this->markTestIncomplete();
        $this->login()->post('/api/calendar_events', $this->attribute)
        ->assertStatus(200)
        ->assertJson([
            ...$this->attribute,
            'start_at' => (new Carbon($this->attribute['start_at']))->toISOString(),
            'end_at' => (new Carbon($this->attribute['end_at']))->toISOString(),
        ]);
        // $calendarEventJson=$response->json();
        // $calendarEvent = CalendarEvent::find($calendarEventJson['id']);
        // $this->assertEquals($calendarEvent->title, $this->attribute['title']);
    }
}
