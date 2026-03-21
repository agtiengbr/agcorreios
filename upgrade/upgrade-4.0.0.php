<?php


function upgrade_module_4_0_0($module)
{
    $module->RemakeWorkers();

    $sqls=[];
    $dbPrefix = _DB_PREFIX_;

    $sqls = [
        "CREATE TABLE {$dbPrefix}agcorreios_tracking (
            id_agcorreios_tracking INT AUTO_INCREMENT,
            id_order INT,
            id_carrier INT,
            tracking_code VARCHAR(255),
            finished tinyint(1) DEFAULT 0 NULL,
            date_add DATETIME,
            date_upd DATETIME,
            PRIMARY KEY (id_agcorreios_tracking)
        );",

        "CREATE TABLE {$dbPrefix}agcorreios_unity (
            id_agcorreios_unity INT AUTO_INCREMENT,
            cod_sro INT,
            type INT,
            city VARCHAR(110),
            state VARCHAR(110),
            date_add DATETIME,
            date_upd DATETIME,
            PRIMARY KEY (id_agcorreios_unity)
        );",

        "CREATE TABLE {$dbPrefix}agcorreios_tracking_events (
            id_agcorreios_tracking_events INT AUTO_INCREMENT,
            id_agcorreios_tracking INT,
            id_agcorreios_unity INT,
            code VARCHAR(25),
            type VARCHAR(25),
            created DATETIME,
            `desc` VARCHAR(255),
            date_add DATETIME,
            date_upd DATETIME,
            PRIMARY KEY (id_agcorreios_tracking_events)
        );"
    ];

    foreach ($sqls as $sql) {
        try {
            Db::getInstance()->execute($sql);
        } catch (Exception $e){}
    }
    
    return true;
}
