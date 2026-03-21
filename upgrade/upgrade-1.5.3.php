<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_5_3($module)
{
    $services = AgCorreiosServices::getAll();

    foreach ($services as $service) {
        $carrier = $service->getCarrier();
        $carrier->shipping_handling = true;
        $carrier->update();
    }
    

    return true;
}
