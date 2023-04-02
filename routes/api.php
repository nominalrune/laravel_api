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
use App\Http\Controllers\CommentController;


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

    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index')->can('viewAny', Task::class);
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('task.show');
    Route::post('/tasks', [TaskController::class, 'store'])->name('task.store')->can('create', Task::class);
    Route::post('/tasks/{id}', [TaskController::class, 'update'])->name('task.update')->can('update', Task::class);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('task.delete')->can('delete', Task::class);

    Route::get('/records', [RecordController::class, 'index'])->name('record.index')->can('viewAny', Record::class);
    Route::get('/records/{id}', [RecordController::class, 'show'])->name('record.show')->can('view', Record::class);
    Route::post('/records', [RecordController::class, 'store'])->name('record.store');
    Route::post('/records/{id}', [RecordController::class, 'update'])->name('record.update')->can('update', Record::class);
    Route::delete('/records/{id}', [RecordController::class, 'destroy'])->name('record.delete')->can('delete', Record::class);

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar_event', [CalendarController::class, 'index'])->name('calendar_event.index');
    Route::get('/calendar_event/{id}', [CalendarController::class, 'show'])->name('calendar_event.show');
    Route::post('/calendar_event', [CalendarController::class, 'store'])->name('calendar_event.store');
    Route::post('/calendar_event/{id}', [CalendarController::class, 'update'])->name('calendar_event.update');
    Route::delete('/calendar_event/{id}', [CalendarController::class, 'destroy'])->name('calendar_event.delete');

    Route::post('/comments', [CommentController::class, 'store'])->name('comment.store');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comment.delete');

});
