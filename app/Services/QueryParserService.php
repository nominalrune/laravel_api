<?php

namespace App\Services;

use Illuminate\Foundation\Http\FormRequest;
/**
 * This class is responsible for parsing the query string from a URL
 * and returning the values of the specified parameters.
 */
class QueryParserService
{
    use 
    protected $url;
    public $queries=[];
    public $rules=[];
    public function url()
    {
        return $this->url;
    }

    /**
     * Initializes the QueryParserService with the specified URL.
     * If the second parameter is specified, only the specified
     * parameters will be returned.
     */
    public function __construct(string $url, ?array $rules=null)
    {
        $this->url = $url;
        $queries = array_map(function(){},);
        $urlData = parse_url($url, PHP_URL_QUERY);

        if (is_string($urlData)) {
            parse_str($urlData, $queries);
        }

        if ($keys) {
            foreach ($keys as $key) {
                $this->queries[$key] = $queries[$key] ?? null;
            }
        } else {
            $this->queries = $queries;
        }
    }

    /**
     * Returns an array of the specified parameters from the URL.
     * If only one parameter is specified, the value of that parameter
     * will be returned instead of an array.
     */
    public function get(string ...$keys) : array
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($this->queries[$key])) {
                $result[$key] = $this->queries[$key];
            }
        }
        if (count($keys) === 1 && count($result) === 1) {
            return $result[0];
        }
        return $result;
    }

    /**
     * Returns true if all of the specified parameters are present in the URL.
     */
    public function fullfills(array $keys)
    {
        foreach ($keys as $key) {
            if (!isset($this->queries[$key])) {
                return false;
            }
        }
        return true;
    }
}
