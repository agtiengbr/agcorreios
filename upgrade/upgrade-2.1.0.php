<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_2_1_0($module)
{
    $options = $module->getOptions();
    $options['agcorreios_precalculate'] = true;

    Configuration::updateValue('AGCORREIOS', serialize($options));

    return true;
}
