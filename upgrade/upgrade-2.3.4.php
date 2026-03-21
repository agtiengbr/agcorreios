<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_2_3_4($module)
{
    Db::getInstance()->update("agcorreios_price", ['shipping_cost' => 0, 'recalculate' => 1]);
    return true;
}