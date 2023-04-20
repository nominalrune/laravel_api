<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::post('/user', [UserController::class, 'store']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);

    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('task.show');
    Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('task.delete');

    Route::get('/records', [RecordController::class, 'index'])->name('record.index');
    Route::get('/records/{record}', [RecordController::class, 'show'])->name('record.show');
    Route::post('/records', [RecordController::class, 'store'])->name('record.store');
    Route::put('/records/{record}', [RecordController::class, 'update'])->name('record.update');
    Route::delete('/records/{record}', [RecordController::class, 'destroy'])->name('record.delete');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar_events', [CalendarController::class, 'index'])->name('calendar_event.index');
    Route::get('/calendar_events/{id}', [CalendarController::class, 'show'])->name('calendar_event.show');
    Route::post('/calendar_events', [CalendarController::class, 'store'])->name('calendar_event.store');
    Route::put('/calendar_events/{id}', [CalendarController::class, 'update'])->name('calendar_event.update');
    Route::delete('/calendar_events/{id}', [CalendarController::class, 'destroy'])->name('calendar_event.delete');

    Route::post('/comments', [CommentController::class, 'store'])->name('comment.store');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comment.delete');

});
