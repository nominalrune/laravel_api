<?php
namespace Tests;

use App\Models\Record;
use App\Models\Task;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ApiTestCase extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected User $anotherUser;
    protected Task $task;
    protected Task $completedTask;
    protected Task $othersTask;
    protected Task $sharedTask;
    protected Record $record;
    protected Record $anotherRecord;
    protected Record $othersRecord;
    protected Record $sharedRecord;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->anotherUser = User::factory()->create();
        $this->task = Task::factory()->create(['user_id' => $this->user->id]);
        $this->completedTask = Task::factory()->create([
            'user_id' => $this->user->id,
            'state' => 1,
        ]);
        $this->othersTask = Task::factory()->create(['user_id' => $this->anotherUser->id]);
        $this->sharedTask = Task::factory()->create(['title'=>'shared task','user_id' => $this->anotherUser->id]);
        PermissionService::setOwnerShip($this->user, $this->sharedTask);
        $this->record = Record::factory()->create(['user_id' => $this->user->id]);
        $this->anotherRecord = Record::factory()->create(['user_id' => $this->user->id]);
        $this->othersRecord = Record::factory()->create(['user_id' => $this->anotherUser->id]);
        $this->withHeaders(['Accept' => 'application/json']);
    }
    protected function login()
    {
        return $this->actingAs($this->user);
    }
}
