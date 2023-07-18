<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use Tests\ApiTestCase;

/**
 * @group task
 * @group task-show
 *
 * @testdox task contoroller show method
 */
class ShowTest extends ApiTestCase
{
    /** @test
     */
    public function cannot_access_task_without_login(): void
    {
        $response = $this->getJson('/tasks/'.$this->task->id);
        $response->assertStatus(401);
    }

    /** gets one task whose owner is the user
     * @test
     *
     * @group task-show */
    public function test_can_access_task_with_login(): void
    {
        $this->login()->getJson('/tasks/'.$this->task->id)
            ->assertStatus(200)
            ->assertJson($this->task->toArray(), true);
    }

    /** gets one task shared with the user
     * @test
     *
     * @group task-show */
    public function can_access_shared_task(): void
    {
        $this->login()->getJson('/tasks/'.$this->sharedTask->id)
            ->assertStatus(200)
            ->assertJson($this->sharedTask->toArray(), true);
    }

    /** gets one task shared with the user
     * @test
     *
     * @group task-show */
    public function cannot_access_others_task(): void
    {
        $this->login()->getJson('/tasks/'.$this->othersTask->id)
            ->assertStatus(404)
            ->assertJsonMissing($this->othersTask->toArray(), true);
    }
}
