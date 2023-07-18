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
        $this->reportable(function (\Exception $e) {
            if (! $this->isHttpException($e)) {
                $this->log($e);
            }
        });
    }

    private function log(Throwable $e)
    {
        $request = request();
        Log::channel('internalError')->error($e->getMessage(), [
            'request' => [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                // 'headers' => $request->headers, // NOTE optout for a security reason
                'body' => $request->getContent(),
            ],
            'user' => [
                'user_id' => $request->user()->id,
                'ip' => $request->ip(),
            ],
            'trace' => $e->getTraceAsString()
        ]);
    }
}
