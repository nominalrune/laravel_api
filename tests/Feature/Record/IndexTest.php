<?php

namespace Tests\Feature\Report;

use Tests\ApiTestCase;

class IndexTest extends ApiTestCase
{
    /**
     * @test
     */
    public function can_not_access_calendar_without_login(): void
    {
        $this->get('/api/records')->assertStatus(401);
    }
}
