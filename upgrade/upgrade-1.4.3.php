<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_4_3($module)
{
	if (!file_exists(_PS_MODULE_DIR_ . 'agzipcodezones/agzipcodezones.php')) {
        return true;
    }

    require_once _PS_MODULE_DIR_ . 'agzipcodezones/agzipcodezones.php';
    $obj = new agzipcodezones;


    $sql = new DbQuery;
    $sql->select('id_zone')
        ->from('zone')
        ->where('name="Rio de Janeiro - Interior"');

    $id_zone = Db::getInstance()->getValue($sql);

    if ($id_zone) {
        $intervals = AgZipcodeZonesInterval::getByZone($id_zone);

        foreach ($intervals as $interval) {
            $interval->zipcode_begin = '26601000';
            $interval->zipcode_end   = '28999999';

            $interval->update();
        }

        $correios_interval = AgCorreiosInterval::getByZone($id_zone);
        $correios_interval->postcode_to_use = '27937010';
        $correios_interval->update();
    }


    $sql = new DbQuery;
    $sql->select('id_zone')
        ->from('zone')
        ->where('name="Rio de Janeiro - Capital"');

    $id_zone = Db::getInstance()->getValue($sql);

    if ($id_zone) {
        $intervals = AgZipcodeZonesInterval::getByZone($id_zone);

        foreach ($intervals as $interval) {
            $interval->zipcode_begin = '20000000';
            $interval->zipcode_end   = '26600999';

            $interval->update();
        }

        $correios_interval = AgCorreiosInterval::getByZone($id_zone);
        $correios_interval->postcode_to_use = '20211110';
        $correios_interval->update();
    }

    return true;
}
