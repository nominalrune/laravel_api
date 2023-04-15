<?php
namespace Tests;

use App\Models\Record;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ApiTestCase extends TestCase{
    use RefreshDatabase;
    protected User $user;
    protected User $anotherUser;
    protected Task $task;
    protected Task $anotherTask;
    protected Record $record;
    protected Record $anotherRecord;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->anotherUser = User::factory()->create();
        $this->task = Task::factory()->create(['user_id' => $this->user->id]);
        $this->withHeaders(['Accept' => 'application/json']);
    }
    protected function login()
    {
        $this->actingAs($this->user);
        return $this;
    }
}
