<?php

namespace App\Logger\Processor;

use ArrayAccess;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class LoginUserProcessor implements ProcessorInterface
{
    /**
     * @var ArrayAccess
     */
    protected $user;

    /**
     */
    public function __construct(array|ArrayAccess|null $serverData = null)
    {
        $this->user = auth()->user();
    }

    /**
     * @param LogRecord $record
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['user'] = $this->user?->toArray();
        return $record;
    }
}
