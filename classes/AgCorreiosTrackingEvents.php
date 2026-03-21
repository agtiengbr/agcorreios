<?php

class AgCorreiosTrackingEvents extends AgObjectModel
{
    public static $definition = array(
        'table'     => 'agcorreios_tracking_events',
        'primary'   => 'id_agcorreios_tracking_events',
        'multilang' => false,
        'fields'    => array(
            'id_agcorreios_tracking_events' => array('type' => self::TYPE_INT),
            'id_agcorreios_tracking' => array('type' => self::TYPE_INT, 'db_type' => 'integer'),
            'id_agcorreios_unity' => array('type' => self::TYPE_INT, 'db_type' => 'integer'),
            'code' => array('type' => self::TYPE_STRING,  'db_type' => 'varchar(25)'),
            'type' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(25)'),
            'created' => array('type' => self::TYPE_DATE, 'db_type' => 'datetime'),
            'desc' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_upd' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime']
        )
    );

    public $id_agcorreios_tracking_events;
    public $id_agcorreios_tracking;
    public $id_agcorreios_unity;
    public $code;
    public $type;
    public $created;
    public $desc;
    public $date_add;
    public $date_upd;

   
    public static function get($id_agcorreios_tracking,$code,$type,$id_correios_unity)
    {
        $query = new DbQuery();
        $query = $query->from('agcorreios_tracking_events')
        ->where('id_agcorreios_tracking="' . pSQL($id_agcorreios_tracking) . '"')
        ->where('code="' . pSQL($code) . '"')
        ->where('type="' . pSQL($type) . '"')
        ->where('id_agcorreios_unity="' . pSQL($id_correios_unity) . '"');

        $event = Db::getInstance()->getRow($query);
        
        $return = new AgCorreiosTrackingEvents();
        if (is_array($event)) {
            $return->hydrate($event);
        }        
       
        return $return;
    }

    public static function getEventTypes(){
        $query = new DbQuery();
        $query = $query->select('DISTINCT `desc`,code')->from('agcorreios_tracking_events');
          
        return Db::getInstance()->executeS($query);

    }
}
