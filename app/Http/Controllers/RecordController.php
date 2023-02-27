<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Models\Record;
use Illuminate\Http\Request;

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
        // Create a new Eloquent query
        $eloquent = Record::query();
        $eloquent->whereHas('permissions', function ($query) {
            $query->where('user_id', auth()->id())
                ->where('permission_type', 'read');
        });
        // Define the possible queries and their data types
        $possible_queries = [
            ['started_at', 'timestamp'],
            ['ended_at', 'timestamp'],
            ['user_id', 'int'],
            ['status', 'int'],
            ['parent_task_id', 'int'],
            ['title', 'string'],
            ['description', 'string'],
        ];
        // Iterate through the possible queries
        foreach ($possible_queries as $query) {
            $field = $query[0];
            $type = $query[1];

            // Check if the query exists in the request
            if ($request->has($field)) {
                $value = $request->input($field);

                // Check if the query is a timestamp range
                if ($type === 'timestamp') {
                    if (is_array($value)) {
                        $from = $value[0];
                        $to = $value[1];
                        // Add timestamp range to query
                        if ($from == null) {
                            $eloquent->where($field, '<=', $to);
                        } elseif ($to == null) {
                            $eloquent->where($field, '>=', $from);
                        } else {
                            $eloquent->whereBetween($field, [$from, $to]);
                        }
                    }
                } elseif ($type === 'string') {
                    // Add string query to Eloquent
                    $eloquent->where($field, 'like', '%' . $this->escapeLike($value) . '%');
                } else {
                    // Add integer query to Eloquent
                    $eloquent->where($field, $value);
                }
            }
        }
        // Order by updated date
        $eloquent->orderBy('updated_at', 'desc');
        // pagenate the records by 10
        $records = $eloquent->paginate(10);
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
        $record = Record::create($request->validated());
        return $record;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function show(Record $record)
    {
        $this->authorize('view', $record);
        return $record->with('related_task');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRecordRequest  $request
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecordRequest $request, Record $record)
    {
        $this->authorize('update', $record);
        $record->update($request->validated());
        return $record->with('related_task');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        $this->authorize('delete', $record);
        $record->delete();
        return response()->noContent();
    }
}
