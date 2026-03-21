<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_4_2($module)
{
	foreach (AgCorreiosServices::getAll() as $service) {
		$carrier = new Carrier($service->carrier_id);
		$carrier->url = Context::getContext()->shop->getBaseURL() . 'modules/agcorreios/controllers/front/rastreamento.php?objeto=@';
		$carrier->update();
	}
	
    return true;
}
