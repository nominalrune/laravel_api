<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::debug("RecordController@index", ['request' => $request->all(),'date'=>$request->date('month')]);
        $date=($request->date('month')??now());
        $date_start=$date->copy()->startOfMonth()->toDateString();
        $date_end=$date->copy()->endOfMonth()->toDateString();
        $records=Record::whereBetween('date',[$date_start,$date_end])->with(['comments'])->get();
        Log::debug("RecordController@index", ['date_end'=>$date_end,'records' => $records]);
        return $records;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRecordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecordRequest $request)
    {
        Log::debug("RecordController@store", ['request' => $request->all()]);
        $record = Record::create($request->validated());
        return $record;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $record=Record::find($id);
        $record->loadMissing(['comments']);
        Log::debug("RecordController@show", ['record' => $record]);
        // $this->authorize('view', $record);
        return $record;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRecordRequest  $request
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecordRequest $request, int $id)
    {
        // $this->authorize('update', $record);
        $record = Record::find($id);
        $record->update($request->validated());
        return $record->with('related_task');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $record = Record::find($id);
        $this->authorize('delete', $record);
        $record->delete();
        return response()->noContent();
    }
}
