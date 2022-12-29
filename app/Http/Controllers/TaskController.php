<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\TaskAcl;
use Illuminate\Database\QueryException;
use App\Models\Task;
use App\Http\Resources\Task\TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tasks = TaskAcl::where('user_id', auth()->user()->id)
                ->reject(fn ($acl) => $acl->read === false)
                ->map(fn ($acl) => $acl->task);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create($request->validated());
            TaskAcl::create([
                'task_id' => $task->id,
                'user_id' => auth()->user()->id,
                'read' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
                'share' => true,
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        TaskAcl::where('task_id', $task->id)
            ->where('user_id', auth()->user()->id)
            ->where('update', true)
            ->firstOrFail();
        try {
            $task->update($request->validated());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return response()->noContent();
    }
}
