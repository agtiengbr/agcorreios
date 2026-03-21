<?php


function upgrade_module_3_0_8($module)
{
    $module->RemakeWorkers();
    return true;
}
