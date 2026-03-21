<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_2_2_0($module)
{
    Db::getInstance()->execute('truncate table '. _DB_PREFIX_ . 'agcorreios_price');

    $models = array(
        'AgCorreiosServices',
        'AgCorreiosPrices',
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

    $services = AgCorreiosServices::getAll();
    foreach ($services as $service) {
        $service->max_width = 105;
        $service->max_depth = 105;
        $service->max_height = 105;
        $service->max_sum_dimensions = 200;
        $service->weights = implode(',', array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30));

        $service->update();
    }

    return true;
}
