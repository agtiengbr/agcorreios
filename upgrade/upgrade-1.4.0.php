<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_4_0($module)
{
	$module->uninstallOverrides();

	@unlink(_PS_MODULE_DIR_ . 'agcorreios/override/classes/Cart.php');
	@rmdir(_PS_MODULE_DIR_ . 'agcorreios/override/classes');
	@rmdir(_PS_MODULE_DIR_ . 'agcorreios/override');

	@unlink(_PS_MODULE_DIR_ . 'agcorreios/controllers/front/Simulate.php');

	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/js/agcorreios.js');
	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/js/admin_orders.js');
	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/js/admin_orders.js');
	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/js/jquery.mask.min.js');
	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/js/jquery.mask.mod.js');
	@rmdir(_PS_MODULE_DIR_ .  'agcorreios/views/js');

	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/css/agcorreios.css');
	@rmdir(_PS_MODULE_DIR_ .  'agcorreios/views/css');

	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/templates/hook/vars.tpl');
	@unlink(_PS_MODULE_DIR_ . 'agcorreios/views/templates/hook/hookProductButtons.tpl');
	@rmdir(_PS_MODULE_DIR_ .  'agcorreios/views/templates/hook');

	$agcliente = new agcliente;
	$agcliente->installOverrides();

    return true;
}
