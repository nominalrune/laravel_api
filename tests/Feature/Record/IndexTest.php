<?php

namespace Tests\Feature\Report;

use App\Models\ReportEvent;
use App\Models\User;
use Tests\ApiTestCase;
use Illuminate\Support\Carbon;
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
