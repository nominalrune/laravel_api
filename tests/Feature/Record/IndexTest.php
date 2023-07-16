<?php

namespace Tests\Feature\Record;

use Tests\ApiTestCase;

class IndexTest extends ApiTestCase
{
    /**
     * @test
     */
    public function can_not_access_calendar_without_login(): void
    {
        $this->getJson('/records')->assertStatus(401);
    }
}
