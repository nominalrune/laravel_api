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
        switch($request->string('range','')){
            case 'all':
                $recordsQuery=PermissionService::getAllAccessible($request->user(), Record::class,Permission::READ,true);
                break;
            case 'shared':
                $recordsQuery=PermissionService::getShared($request->user(), Record::class,Permission::READ,true);
                break;
            case 'mine':
            default:
                $recordsQuery=$request->user()->records();
                break;
        }

        $date=($request->date('month')??now());
        $date_start=$date->copy()->startOfMonth()->toDateString();
        $date_end=$date->copy()->endOfMonth()->toDateString();
        $records=$recordsQuery->whereBetween('date',[$date_start,$date_end])
        ->with(['comments'])->get();

        return response()->json($records);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RecordRequest $request)
    {
        $record = Record::create($request->validated());
        PermissionService::setOwnerShip($request->user(), $record);
        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(RecordRequest $request, Record $record)
    {
        if($request->user()->can(Permission::READ, $record )){
            $record->load('comments');
            return response()->json($record);
        }else{
            return response(status:404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(RecordRequest $request, Record $record)
    {
        $record->update($request->validated());
        return response()->json($record->load('related_task'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecordRequest $request, Record $record)
    {
        $record->delete();
        return response()->noContent();
    }
}
