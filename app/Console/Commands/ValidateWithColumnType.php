<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ValidateWithColumnType
{
    protected function validate(Builder $table, string $tableName, mixed $data)
    {

        $columns = Schema::getColumnListing($tableName);
        foreach ($data as $key => $value) {
            $cols=implode(',', $columns);
            $this->info("{$tableName}: validating, columns:{$cols}");
            if (! in_array($key, $columns)) {
                throw new \Exception("Validation Error. Column ${key} not found in ${cols} ");
            }
            $type = Schema::getColumnType($tableName, $key);
            if(is_null($value)){
                continue;
            }
            if (in_array($type, ['int', 'bigint'], true) && ! is_int($value)) {
                throw new \Exception("Validation Error. Type mismatch: ${key} is not ${type}");
            }
            if (in_array($type, ['string', 'char', 'text'], true) && ! is_string($value)) {
                throw new \Exception("Validation Error. Type mismatch: ${key} is not ${type}");
            }
            if ($type === 'boolean' && ! is_bool($value)) {
                throw new \Exception("Validation Error. Type mismatch: ${key} is not boolean");
            }
            if ($type === 'datetime' && ! Carbon::canBeCreatedFromFormat($value, 'Y-m-d H:i:s')) {
                throw new \Exception("Validation Error. Type mismatch: ${key} is not datetime");
            }
            if ($type === 'json' && is_string($value)) {
                try {
                    json_decode($value, null, 512, JSON_THROW_ON_ERROR);
                } catch (\Exception $e) {
                    throw new \Exception("Validation Error. Type mismatch: ${key} is not valid json");
                }
            }
        }
    }
}
