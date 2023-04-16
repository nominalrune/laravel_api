<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->renderable(function (ModelNotFoundException $e, Request $request) {
        //     return response(status: 404)->withException($e);
        // });
        // $this->renderable(function (ValidationException $e, Request $request) {
        //     return response(status: 422);
        // });
        $this->renderable(function (QueryException $e, Request $request) {
            $this->log($e, $request);
            return response()->json(['message' => 'Failed to proccess invalid request.'], 422);
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    private function log(Throwable $e, Request $request)
    {
        Log::error($e->getMessage(), ['request' => 'user_id: ' . $request->user()->id . ', url: ' . $request->fullUrl() . ', method: ' . $request->method() . ', ip: ' . $request->ip(), 'trace' => $e->getTraceAsString()]);
    }
}
