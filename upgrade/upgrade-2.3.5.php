<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_3_5($module)
{
    $object_models = [
        'AgCorreiosDiscount'
    ];
    
    foreach ($object_models as $class) {
        $modelInstance = new $class;

        if (method_exists($class, 'createDatabase')) {
            $modelInstance->createDatabase();
        }

        if (method_exists($class, 'createMissingColumns')) {
            $modelInstance->createMissingColumns();
        }

        if (method_exists($class, 'createIndexes')) {
            $modelInstance->createIndexes();
        }

        if (method_exists($class, 'createDefaultData')) {
            $class::createDefaultData();
        }
    }
    
    Db::getInstance()->update("agcorreios_discount", ['id_shop' => 1]);
    return true;
}
