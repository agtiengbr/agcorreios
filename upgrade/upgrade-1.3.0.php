<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_3_0($module)
{
    Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'agcorreios_price');
    Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'agcorreios_price DROP COLUMN id_agcorreios_package');

    $models = array(
        'AgCorreiosPrices'
    );

    foreach ($models as $class) {
        require_once _PS_MODULE_DIR_ . $module->name . '/classes/' . $class . '.php';
        //instantiate the module
        $modelInstance = new $class();

        //create the table relative to this model in the database
        //if the table does not exists yet
        $modelInstance->createDatabase();

        //if the table already exists, add to it any column that may be missing.
        //this is useful in the case of new updates that require new columns
        //to exist in the table.
        $modelInstance->createMissingColumns();

        $modelInstance->createIndexes();
    }
    
    return true;
}
