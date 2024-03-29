<?php

namespace Tests\Feature\CalendarEvent;

use Tests\ApiTestCase;

class IndexTest extends ApiTestCase
{
    /**
     * @test
     */
    public function can_not_access_calendar_events_without_login(): void
    {
        $this->getJson('/calendar_events')->assertStatus(401);
    }

    /**
     * @test
     */
    public function can_access_calendar_events_with_login(): void
    {
        $this->login()->getJson('/calendar_events')->assertStatus(200);
    }
}
