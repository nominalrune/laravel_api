<?php

namespace App\Logger\Formatter;

use Illuminate\Http\Request;
use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class ResponseCodeFormatter implements FormatterInterface{
    /**
     * @param Request $request
     * @param array $response
     * @return string
     */
    public function format(LogRecord $record): string
    {
        return json_encode([
            $record->datetime->format('Y-m-d\TH:i:s'),
            $record->context['url'],
            (int)$record->context['code'],
            $record->context['id'],
            $record->context['ip'],
            $record->context['header'],
        ]) . ',';
    }

    public function formatBatch(array $records): string
    {
        return implode(",", array_map([$this, 'format'], $records));
    }
}
