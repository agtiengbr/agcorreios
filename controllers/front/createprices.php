<?php
require_once _PS_MODULE_DIR_ . 'agcorreios/classes/AgCorreiosPrices.php';
class agcorreioscreatepricesModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if (!Tools::getValue('debug')) {
            $id_worker = Tools::getValue('id_agworker');
            global $agti_worker;
            $agti_worker = new AgClienteWorker($id_worker);
            $agti_worker->save();
        }

        set_time_limit(0);
        ignore_user_abort(true);

        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agcorreios/logs/create_prices.log', 1);
        AgCorreiosPrices::createAll();

        exit();
    }
}