<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
use App\Models\Task;
use App\Models\Record;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CalendarController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // Log::debug("access to api", ['user' => auth()->user(), 'route' => request()->path(), 'method' => request()->method(), 'header' => request()->header(), 'input' => request()->all() ]);
    Route::get('/user', [UserController::class, 'index'])->can('viewAny', User::class);
    Route::get('/user/{id}', [UserController::class, 'show'])->can('view', User::class);
    Route::post('/user', [UserController::class, 'store'])->can('create', User::class);
    Route::post('/user/{id}', [UserController::class, 'update'])->can('update', User::class);
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->can('delete', User::class);

    Route::get('/tasks', [TaskController::class, 'index'])->can('viewAny', Task::class);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks', [TaskController::class, 'store'])->can('create', Task::class);
    Route::post('/tasks/{id}', [TaskController::class, 'update'])->can('update', Task::class);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->can('delete', Task::class);

    Route::get('/records', [RecordController::class, 'index'])->can('viewAny', Record::class);
    Route::get('/records/{id}', [RecordController::class, 'show'])->can('view', Record::class);
    Route::post('/records', [RecordController::class, 'store'])->can('create', Record::class);
    Route::post('/records/{id}', [RecordController::class, 'update'])->can('update', Record::class);
    Route::delete('/records/{id}', [RecordController::class, 'destroy'])->can('delete', Record::class);

    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::get('/calendar/{id}', [CalendarController::class, 'show']);
    Route::post('/calendar', [CalendarController::class, 'store']);
    Route::post('/calendar/{id}', [CalendarController::class, 'update']);
    Route::delete('/calendar/{id}', [CalendarController::class, 'destroy']);
});
