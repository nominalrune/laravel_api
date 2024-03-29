<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Permission;
use App\Models\Task;
use App\Services\PermissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TaskRequest $request)
    {
        Log::debug('request: ', ['path' => request()->path(), 'method' => request()->method(), 'user' => $request->user()]);

        switch ($request->string('range', '')) {
            case 'all':
                $tasksQuery = PermissionService::getAllAccessible(
                    $request->user(),
                    Task::class,
                    Permission::READ,
                    true
                );
                break;
            case 'shared':
                $tasksQuery = PermissionService::getShared(
                    $request->user(),
                    Task::class,
                    Permission::READ,
                    true
                );
                break;
            case 'mine':
            default:
                $tasksQuery = $request->user()->tasks();
                break;
        }
        $request->whenFilled(
            'month',
            function (Carbon $month) use ($tasksQuery) {
                $date_start = $month->copy()->startOfMonth()->tomonthString();
                $date_end = $month->copy()->endOfMonth()->toDateString();
                $tasksQuery->whereBetween('due', [$date_start, $date_end]);
            }
        );

        return response()->json($tasksQuery->with(['comments', 'parentTask'])->get());
    }

    public function show(TaskRequest $request, Task $task)
    {
        if (! PermissionService::can($request->user(), Permission::READ, $task)) {
            return abort(404);
        } else {
            $task->load('comments');
            return response()->json($task);
        }
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());
        PermissionService::setOwnerShip($request->user(), $task);
        return response()->json($task, 201);
    }


    public function update(TaskRequest $request, int $id)
    {
        $task = Task::findOrFail($id);
        if (! PermissionService::can($request->user(), Permission::UPDATE, $task)) {
            abort(404);
        }
        $task->update($request->validated());
        return response()->json($task->load('parentTask'));
    }

    public function destroy(TaskRequest $request, int $id)
    {
        $task = Task::find($id);
        if (! PermissionService::can($request->user(), Permission::DELETE, $task)) {
            return response(status: 404);
        }

        $task->delete();

        return response()->noContent();
    }
}
