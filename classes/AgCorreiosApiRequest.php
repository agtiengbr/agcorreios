<?php

class AgCorreiosApiRequest extends AgObjectModel
{
    public static $definition = [
        'table'   => 'ag_correios_api_request',
        'primary' => 'id',
        'multilang' => false,
        'fields'  => [
            'id' => ['type' => self::TYPE_INT,'validate' => 'isInt'],
            'endpoint' => ['type' => self::TYPE_STRING,'db_type' => 'varchar(2048)','required' => true],
            'headers' => ['type' => self::TYPE_STRING,'db_type' => 'text'],
            'method' => ['type' => self::TYPE_STRING,'db_type' => 'varchar(15)','required' => true],
            'stack_trace' => ['type' => self::TYPE_STRING,'db_type' => 'text'],
            'body' => ['type' => self::TYPE_HTML,'db_type' => 'text'],
            'http_code' => ['type' => self::TYPE_INT,'db_type' => 'int unsigned'],
            'response' => ['type' => self::TYPE_HTML,'db_type' => 'MEDIUMTEXT'],
            'date_add' => ['type'     => self::TYPE_DATE, 'validate' => 'isDate', 'db_type'  => 'datetime'],
            'time_spent' => ['type' => self::TYPE_FLOAT, 'db_type' => 'float']
        ]
    ];


    public $id;
    public $endpoint;
    public $headers;
    public $method;
    public $body;
    public $http_code;
    public $response;
    public $date_add;  
    public $time_spent;
}