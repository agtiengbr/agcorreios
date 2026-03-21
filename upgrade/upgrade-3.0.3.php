<?php


function upgrade_module_3_0_3($module)
{
    require_once _PS_MODULE_DIR_ . 'agcliente/agcliente.php';

    try {
        $agcliente = new agcliente;
        $agcliente->updateModuleTables($module);
        $module->installWorkers();
        $module->RemakeMenus();
    } catch (Exception $e) {
    }

    return true;
}
