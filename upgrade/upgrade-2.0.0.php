<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_2_0_0($module)
{
    //remover as tabelas do banco e recriar!!
    $db_prefix = _DB_PREFIX_;

    $sqls = [
        "DROP TABLE {$db_prefix}agcorreios_interval",
        "DROP TABLE {$db_prefix}agcorreios_price",
        "DROP TABLE {$db_prefix}agcorreios_discount"
    ];

    foreach ($sqls as $sql) {
        Db::getInstance()->execute($sql);
    }

    $module->uninstall();
    $module->install();

    return true;
}
