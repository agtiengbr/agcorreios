<?php

class AgCorreiosTracking extends AgObjectModel
{
    public static $definition = array(
        'table'     => 'agcorreios_tracking',
        'primary'   => 'id_agcorreios_tracking',
        'multilang' => false,
        'fields'    => array(
            'id_agcorreios_tracking' => array('type' => self::TYPE_INT),
            'id_order' => array('type' => self::TYPE_INT, 'db_type' => 'integer'),
            'id_carrier' => array('type' => self::TYPE_INT, 'db_type' => 'integer'),
            'tracking_code' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),
            'service_code' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),
            'id_remote' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),
            'finished' => array('type' => self::TYPE_INT,'db_type' => 'tinyint(1)'),
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_upd' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'solicitar_coleta' => ['type' => self::TYPE_BOOL, 'db_type' => 'boolean'],
            'logistica_reversa' => ['type' => self::TYPE_BOOL, 'db_type' => 'boolean'],
            'prazo_postagem' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'status_atual' =>  array('type' => self::TYPE_INT,'db_type' => 'tinyint(1)')
        )
    );

    public $id_agcorreios_tracking;
    public $id_order;
    public $id_carrier;
    public $tracking_code;
    public $service_code;
    public $id_remote;
    public $finished;
    public $date_add;
    public $date_upd;
    public $solicitar_coleta;
    public $logistica_reversa;
    public $prazo_postagem;
    public $status_atual;

    public static function getByTrackingCode($tracking_code)
    {
        $query = new DbQuery();
        $query = $query->from('agcorreios_tracking')->where('tracking_code="' . pSQL($tracking_code) . '"')->where("finished = 0 OR finished IS NULL");

        $tracking = Db::getInstance()->getRow($query);
        $return = new \AgCorreiosTracking();

        if (is_array($tracking)) {
            $return->hydrate($tracking);
        }
    
        return $return;
    }

    public static function getByOrder($id_order)
    {
        $query = new DbQuery();
        $query = $query->from('agcorreios_tracking')->where('finished = 0')->where('id_order="' . pSQL($id_order) . '"');

        $trackings = Db::getInstance()->executeS($query);

        $return=[];
        foreach ($trackings as $tracking) {
            $obj = new AgCorreiosTracking();
            $obj->hydrate($tracking);
            $return[]=$obj;
        }
        return $return;
    }

    public static function getAllToBeTracked()
    {
        $query = new DbQuery();
        $query = $query->from('agcorreios_tracking')->where('finished = 0 OR finished IS NULL');

        $trackings = Db::getInstance()->executeS($query);
        
        $returns = [];
        foreach ($trackings as $tracking) {
            $return = new AgCorreiosTracking();

            if (is_array($tracking)) {
                $return->hydrate($tracking);
                $returns[] = $return;
            }        
        }
       
        return $returns;
    }

    public static function getFullTrackingEvents($trackingCode)
    {
        $query = new DbQuery();
        $query = $query->select('events.code,events.type,events.desc,events.created,unity.type,unity.city,unity.state')->from('agcorreios_tracking')->where('tracking_code = "'.$trackingCode.'"')
            ->join('LEFT JOIN ' . _DB_PREFIX_ . 'agcorreios_tracking_events events ON `'._DB_PREFIX_.'agcorreios_tracking`.id_agcorreios_tracking = events.id_agcorreios_tracking')
            ->join('LEFT JOIN ' . _DB_PREFIX_ . 'agcorreios_unity unity ON events.id_agcorreios_unity = unity.id_agcorreios_unity')
            ->orderBy('events.created DESC');

        $trackings = Db::getInstance()->executeS($query);
        
        return $trackings;
    }
}
