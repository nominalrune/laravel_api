<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordRequest;
use App\Models\Permission;
use App\Models\Record;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RecordRequest $request)
    {
        $date=($request->date('month')??now());
        $date_start=$date->copy()->startOfMonth()->toDateString();
        $date_end=$date->copy()->endOfMonth()->toDateString();
        switch($request->string('show','')){
            case 'all':
                $recordsQuery=PermissionService::getAllAccessible($request->user(), Record::class,true)->with(['comments']);
                break;
            case 'shared':
                $recordsQuery=PermissionService::getShared($request->user(), Record::class,Permission::READ,true)->with(['comments']);
                break;
            default:
                $recordsQuery=$request->user()->records()
                ->whereBetween('date',[$date_start,$date_end])
                ->with(['comments']);
                break;
        }

        return response()->json($records);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RecordRequest $request)
    {
        // Log::debug("RecordController@store", ['request' => $request->all()]);
        $record = Record::create($request->validated());
        PermissionService::setOwnerShip($request->user(), $record);
        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(RecordRequest $request, int $id)
    {
        $record=Record::find($id);
        Permission::where('target_type',Record::class)
        ->where('target_id',$id)
        ->where('user_id',$request->user()->id)
        ->where('permission_type','read')->firstOrFail();
        $record->loadMissing(['comments']);
        // Log::debug("RecordController@show", ['record' => $record]);
        // $this->authorize('view', $record);
        return $record;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RecordRequest $request, int $id)
    {
        // $this->authorize('update', $record);
        $record = Record::find($id);
        $record->update($request->validated());
        return $record->with('related_task');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecordRequest $request, int $id)
    {
        $record = Record::find($id);
        $this->authorize('delete', $record);
        $record->delete();
        return response()->noContent();
    }
}
