<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_2_1($module)
{
    Configuration::updateValue('AGCORREIOS_ZIPCODE_ORIGIN', Configuration::get('PS_SHOP_CODE'));

    return true;
}
