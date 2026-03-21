<?php
class AgCorreiosConcilBatch extends AgObjectModel
{
    public static $definition = [
        'table'     => 'agcorreios_concil_batch',
        'primary'   => 'id_agcorreios_concil_batch',
        'multilang' => false,
        'fields'    => [
            'id_agcorreios_concil_batch'   => ['type' => self::TYPE_INT, 'db_type' => 'int', 'validate' => 'isInt'],
            'status' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_analysis_begin' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_analysis_end' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'total_rows' => ['type' => self::TYPE_INT, 'db_type' => 'integer'],
            'rows_not_found' => ['type' => self::TYPE_INT, 'db_type' => 'integer'],
            'rows_price_error' => ['type' => self::TYPE_INT, 'db_type' => 'integer'],
            'rows_ok' => ['type' => self::TYPE_INT, 'db_type' => 'integer'],
        ],
    ];

    public $id_agcorreios_concil_batch;
    public $status;
    public $date_add;
    public $date_analysis_begin;
    public $date_analysis_end;
    
    public $total_rows;
    public $rows_not_found;
    public $rows_price_error;
    public $rows_ok;
}