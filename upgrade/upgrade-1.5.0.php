<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_5_0($module)
{
    Configuration::updateValue('AGCORREIOS_OFFLINE', 1);

    try {
    	Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'agcorreios_price ADD COLUMN date_last_update datetime');
    } catch (Exception $e) {}

    try {
    	Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'agcorreios_price ADD COLUMN force_recalculate boolean');
    } catch (Exception $e) {}

    return true;
}
