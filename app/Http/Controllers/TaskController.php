<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Permission;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->tasks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        $request->mergeIfMissing(['owner_id' => $request->user()->id]);
        $inputs = $request->validated();
        $task = Task::create($inputs);
        if ($task->owner_id != $request->user()->id) {
            foreach (['read', 'write', 'delete'] as $permission) {
                Permission::create([
                    'user_id' => $request->user()->id,
                    'target_type' => Task::class,
                    'target_id' => $task->id,
                    'permission_type' => $permission,
                ]);
            }
        }

        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $task = Task::findOrFail($request->id);
        // $this->authorize('view', $task); //FIXME not working
        Log::debug(['task' => $task]);
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
        $this->authorize('update', $task);
        $task->update($request->validated());
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
        $this->authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }
}
