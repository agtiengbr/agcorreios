<?php

use AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\Unidade;

class AgCorreiosUnity extends AgObjectModel
{
    public static $definition = array(
        'table'     => 'agcorreios_unity',
        'primary'   => 'id_agcorreios_unity',
        'multilang' => false,
        'fields'    => array(
            'id_agcorreios_unity' => array('type' => self::TYPE_INT),
            'cod_sro' => array('type' => self::TYPE_INT, 'db_type' => 'integer'),
            'type' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(110)'),
            'city' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(110)'),
            'state' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(110)'),
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_upd' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime']
        )
    );

    public $id_agcorreios_unity;
    public $cod_sro;
    public $type;
    public $city;
    public $state;
    public $date_add;
    public $date_upd;

    public static function get(Unidade $unidade){

        $query = new DbQuery();
        $query = $query->from('agcorreios_unity');

        if($unidade->getCodSro()){
            $query = $query->where('cod_sro="' . pSQL($unidade->getCodSro()) . '"');
    
        }else{
            $query = $query->where('type="' . pSQL($unidade->getTipo()) . '"');
            $query = $query->where('city="' . pSQL($unidade->getEndereco()->getCidade()) . '"');
            $query = $query->where('state="' . pSQL($unidade->getEndereco()->getUf()) . '"');
        }

        $return = new AgCorreiosUnity();
        $unity = Db::getInstance()->getRow($query);
    
        if (is_array($unity)) {
            $return->hydrate($unity);
        }
    
        return $return;

    }

   
}
