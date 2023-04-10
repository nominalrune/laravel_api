<?php
namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ApiTestCase extends TestCase{
    use RefreshDatabase;
    protected User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->withHeaders(['Accept' => 'application/json']);
    }
    protected function login()
    {
        $this->actingAs($this->user);
        return $this;
    }
}
