<?php

namespace App\Http\Requests;


use Illuminate\Http\Request as BaseRequest;

/**
 * @template T
 * @extends BaseRequest<T>
 * @method mixed input(
 *  $key, mixed $default = null)
 * @method T all()
 */
class Request extends BaseRequest{

}

/**
 * @param Request<array{name: string, }> $request
 */
function contr(Request $request) {
    $request->input('foo');
    $request->input('bar');
    $request->input('baz');
    $request->all();
}
