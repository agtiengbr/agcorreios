<?php
function upgrade_module_5_1_7($module)
{
    $module->registerHook('actionObjectOrderCarrierUpdateAfter');
    return true;
}