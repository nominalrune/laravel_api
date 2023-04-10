<?php

namespace Tests\Feature\api\calendar;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Tests\ApiTestCase;

class IndexTest extends ApiTestCase
{
    /**
     * @test
     */
    public function can_not_access_calendar_events_without_login(): void
    {
        $response = $this->get('/api/calendar_events');
        $response->assertStatus(401);
    }
    /**
     * @test
     */
    public function can_access_calendar_events_with_login(): void
    {
        $response = $this->login()->get('/api/calendar_events');

        $response->assertStatus(200);
    }
}
