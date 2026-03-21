<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_6_0($module)
{
    $models = array(
        'AgCorreiosDiscount'
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

    if ($module->ps16) {
        $tabModel             = new Tab();
        $tabModel->module     = $module->name;
        $tabModel->active     = 1;
        $tabModel->class_name = 'AdminAgCorreiosDiscounts';
        $tabModel->id_parent  = Tab::getIdFromClassName('AdminParentShipping');

        foreach (\Language::getLanguages(true) as $lang) {
            $tabModel->name[$lang['id_lang']] = 'Correios - Descontos';
        }
        
        $tabModel->add();
    } else {
        $tabModel             = new Tab();
        $tabModel->module     = $module->name;
        $tabModel->active     = 1;
        $tabModel->class_name = 'AdminAgCorreiosDiscounts';
        $tabModel->id_parent  = Tab::getIdFromClassName('AdminAgCorreios');

        foreach (\Language::getLanguages(true) as $lang) {
            $tabModel->name[$lang['id_lang']] = 'Descontos';
        }
        
        $tabModel->add();
    }
    
    return true;
}
