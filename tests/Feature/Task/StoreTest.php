<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use Carbon\Carbon;
use Tests\ApiTestCase;

/**
 * @group task
 * @group task-store
 */
class StoreTest extends ApiTestCase
{
    protected $validParams = [
        'title' => 'test title',
        'description' => 'test description',
        'state' => 0,
        'due' => '2021-01-01',
        'subtasks' => [],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->validParams['user_id'] = $this->user->id;
        // $this->invalidParams['user_id'] = $this->user->id;
    }

    /** @test
     */
    public function cannot_create_task_without_login(): void
    {
        $response = $this->post('/api/tasks/', $this->validParams);
        $response->assertStatus(401);
    }

    /** creates one task
     * @test
     *
     * @group task-show */
    public function can_create_task_with_login(): void
    {
        $this->login()->post('/api/tasks/', $this->validParams)
            ->assertStatus(201)
            ->assertJson([...$this->validParams, 'due' => Carbon::parse($this->validParams['due'])->toISOString()]);
    }

    /** fail to create task with invalid params
     * @test
     *
     * @group task-show */
    public function cannot_create_task_with_invalid_params(): void
    {
        $invalidParam = [
            ...$this->validParams,
            'title' => null,
            'description' => 'over 50000'.str_pad('', 50000, 'a'),
            'state' => -1,
            'due' => '2021-01-99',
            'subtasks' => [
                [
                    'title' => '',
                    'state' => 11,
                    'subtasks' => [
                        [
                            'title' => '',
                            'state' => 0,
                            'subtasks' => [],
                        ],
                    ],
                ], [
                    'title' => 'over 255'.str_pad('', 255, 'a'),
                    'state' => 0,
                ],
            ],
        ];
        $this->login()->post('/api/tasks/', $invalidParam)->dump()
            ->assertStatus(422)
            ->assertInvalid(['title', 'due', 'description', 'subtasks.0.title', 'subtasks.0.state', 'subtasks.1.title']);
    }
}
