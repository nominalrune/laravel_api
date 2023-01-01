<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserGroupController;
use App\Models\Task;
use App\Models\Record;
use App\Models\UserGroup;
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
Route::get('/hi',function(){return json_encode(["hi"=>"Hello."]);});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', [UserController::class, 'index'])->can('viewAny', User::class);
    Route::get('/user/{user}', [UserController::class, 'show'])->can('view', User::class);
    Route::post('/user', [UserController::class, 'store'])->can('create', User::class);
    Route::put('/user/{user}', [UserController::class, 'update'])->can('update', User::class);
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->can('delete', User::class);

    Route::get('/user_groups', [UserGroupController::class, 'index'])->can('viewAny', UserGroup::class);
    Route::get('/user_groups/{user_group}', [UserGroupController::class, 'show'])->can('view', UserGroup::class);
    Route::post('/user_groups', [UserGroupController::class, 'store'])->can('create', UserGroup::class);
    Route::put('/user_groups/{user_group}', [UserGroupController::class, 'update'])->can('update', UserGroup::class);
    Route::delete('/user_groups/{user_group}', [UserGroupController::class, 'destroy'])->can('delete', UserGroup::class);


    Route::get('/tasks', [TaskController::class, 'index'])->can('viewAny', Task::class);
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->can('view', Task::class);
    Route::post('/tasks', [TaskController::class, 'store'])->can('create', Task::class);
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->can('update', Task::class);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->can('delete', Task::class);

    Route::get('/records', [RecordController::class, 'index'])->can('viewAny', Record::class);
    Route::get('/records/{record}', [RecordController::class, 'show'])->can('view', Record::class);
    Route::post('/records', [RecordController::class, 'store'])->can('create', Record::class);
    Route::put('/records/{record}', [RecordController::class, 'update'])->can('update', Record::class);
    Route::delete('/records/{record}', [RecordController::class, 'destroy'])->can('delete', Record::class);

});
