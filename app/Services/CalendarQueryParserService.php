<?php

namespace App\Services;


/** parse query strings.
 *
 **/
class CalendarQueryParserService extends QueryParserService{
    private $rules=[
        'display_type'=>['required','in:month,week,day'],
        'start'=>,
        'end',
        'event_type',
        'user',
        'status_type'
    ];
    public function __construct($url)
    {
        $this->validators=[
        'display_type'=>fn($value)=>in_array($value, ['month','week','day']),
        'start'=>fn($value)=>strtotime($value),
        'end',
        'event_type',
        'user',
        'status_type'
    ];
        parent::__construct($url, $this->validators);
    }

}
