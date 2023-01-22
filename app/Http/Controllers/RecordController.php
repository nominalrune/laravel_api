<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Models\Record;
use Illuminate\Http\Request;
use App\Models\Acl;

class RecordController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function index($request)
	{
        // Get the accessible records for the current user
        $accessible_records= Acl::where('target_table','records')
            ->where('user_group_id', auth()->user()->userGroups->id)
            ->pluck('target')->toArray();

		// Create a new Eloquent query
        $eloquent = Record::query();
        // Define the possible queries and their data types
		$possible_queries = [
			['created_at', 'timestamp'],
			['updated_at', 'timestamp'],
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
					$eloquent->where($field, 'like', '%'.$this->escapeLike($value).'%');
				} else {
					// Add integer query to Eloquent
					$eloquent->where($field, $value);
				}
			}
		}
        // Filter by accessible records
        $eloquent->whereIn('id',$accessible_records);
        // Join the user table
        $eloquent->join('users', 'records.user_id', '=', 'users.id');
        // Order by updated date
        $eloquent->orderBy('updated_at', 'desc');
        // pagenate the records by 10
		$records = $eloquent->cursorPaginate(10);
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
		// $this->authorize('create', Record::class);
        $record=Record::create($request->validated());
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
	}
}
