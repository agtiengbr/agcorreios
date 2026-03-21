<?php
class AgCorreiosConcilRows extends AgObjectModel
{
    public static $definition = [
        'table'     => 'agcorreios_concil_rows',
        'primary'   => 'id_agcorreios_concil_rows',
        'multilang' => false,
        'fields'    => [
            'id_agcorreios_concil_rows'   => ['type' => self::TYPE_INT, 'db_type' => 'int', 'validate' => 'isInt'],
            'tracking_number' => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'],
            'cost' => ['type' => self::TYPE_FLOAT, 'db_type' => 'float'],
            'id_order_carrier' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'id_agcorreios_concil_batch' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'status' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_analysis' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
        ],
        'indexes' => [
            [
                'fields' => ['id_agcorreios_concil_batch', 'id_order_carrier'],
                'name' => 'idx_id_order_carrier'
            ],
            [
                'fields' => ['id_agcorreios_concil_batch', 'status'],
                'name' => 'idx_id_order_carrier'
            ]
        ]
    ];

    public $id_agcorreios_concil_rows;
    public $tracking_number;
    public $cost;
    public $id_order_carrier;
    public $id_agcorreios_concil_batch;
    public $status;
    public $date_add;
    public $date_analysis;

}