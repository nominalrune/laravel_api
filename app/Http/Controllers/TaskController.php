<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\TaskAcl;
use Illuminate\Database\QueryException;
use App\Models\Task;

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
            $tasks= TaskAcl::where('user_id', auth()->user()->id)
            ->with('target')
                ->get()
                ->pluck('target');
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return $tasks;
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
                'target_id' => $task->id,
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
        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        // auth is already handled by the policy class
        return $task;
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
        try { // auth is already handled by the policy class
            $task->update($request->validated());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task $task
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
