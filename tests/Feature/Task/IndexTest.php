<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use Tests\ApiTestCase;

/**
 * @group task
 * @group task-index
 *
 * @testdox task contoroller index method
 */
class IndexTest extends ApiTestCase
{
    public function test_can_not_access_tasks_without_login(): void
    {
        $this->get('/api/tasks')->assertStatus(401);
    }

    public function test_can_access_tasks_with_login(): void
    {
        $this->login()->get('/api/tasks')->assertStatus(200);
    }

    /**
     * @test
     *
     * @group task-index
     * @group task-index-range
     *
     * @testdox `range` urlクエリを`all`にする。全てのタスクが取得できる
     */
    public function test_gets_own_tasks_without_query(): void
    {
        $this->login()->get('/api/tasks')
        ->assertJson([
            $this->task->toArray(), $this->completedTask->toArray(),
        ], true)
        ->assertJsonMissing($this->othersTask->toArray(), true);
    }

    /**
     * @test
     *
     * @group task
     * @group task-index
     * @group task-index-range
     *
     * @testdox `range` urlクエリを`shared`にする。sharedTask`のタイトルのタスクが取得できる。ただし、ほかのタスクは含まない
     */
    public function test_gets_shared_tasks_with_range_is_shared_query(): void
    {
        $this->login()->get('/api/tasks?range=shared')
        ->assertJson([$this->sharedTask->toArray()], true)
        ->assertJsonMissing([
            $this->task->toArray(), $this->task->toArray(),
        ], true);
    }
}
