<?php

namespace Tests;

use App\Models\Record;
use App\Models\Task;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $user01;
    protected User $user02;
    protected Task $task;
    protected Task $completedTask;
    protected Task $othersTask;
    protected Task $sharedTask;
    protected Record $record;
    protected Record $anotherRecord;
    protected Record $othersRecord;
    protected Record $sharedRecord;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user01 = User::factory()->create();
        $this->user02 = User::factory()->create();
        $this->task = Task::factory()->create(['user_id' => $this->user01->id]);
        $this->completedTask = Task::factory()->create([
            'user_id' => $this->user01->id,
            'state' => 1,
        ]);
        $this->othersTask = Task::factory()->create(['user_id' => $this->user02->id]);
        $this->sharedTask = Task::factory()->create(['title' => 'shared task', 'user_id' => $this->user02->id]);
        PermissionService::setOwnerShip($this->user01, $this->sharedTask);
        $this->record = Record::factory()->create(['user_id' => $this->user01->id]);
        $this->anotherRecord = Record::factory()->create(['user_id' => $this->user01->id]);
        $this->othersRecord = Record::factory()->create(['user_id' => $this->user02->id]);
    }

    /**
     * @param ?literal-string['user01','user02'] $as user01 or user02
     */
    protected function login(?string $as = 'user01')
    {
        return $this->actingAs($this->$as);
    }
}
