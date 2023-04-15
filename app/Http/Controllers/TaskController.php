<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Models\Permission;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TaskRequest $request)
    {
        return response()->json($request->user()->tasks);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TaskRequest $request, int $id)
    {
        $task=$request->user()->tasks()->findOrFail($request->id);
        return response()->json($task);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());
        // if ($task->owner_id != $request->user()->id) {
        //     foreach (['read', 'write', 'delete'] as $permission) {
        //         Permission::create([
        //             'user_id' => $request->user()->id,
        //             'target_type' => Task::class,
        //             'target_id' => $task->id,
        //             'permission_type' => $permission,
        //         ]);
        //     }
        // }

        return response()->json($task, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaskRequest $request, int $id)
    {
        $task= $request->user()->tasks()->findOrFail($id);
        $task->update($request->validated());
        // Log::debug(['task' => $task]);
        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskRequest $request, int $id)
    {
        $task= $request->user()->tasks()->findOrFail($id);
        $task->delete();
        return response(status:204);
    }
}
