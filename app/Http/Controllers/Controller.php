<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Escapes all the characters that have special meaning in SQL LIKE clauses.
     *
     * @param  string  $raw_string The string to escape.
     * @param  string  $escape_char The character to use for escaping.
     * @return string The string with special characters escaped
     */
    public function escapeLike(string $raw_string, string $escape_char = '\\'): string
    {
        return str_replace(
            [$escape_char, '%', '_'],
            [$escape_char.$escape_char, $escape_char.'%', $escape_char.'_'],
            $raw_string
        );
    }
}
