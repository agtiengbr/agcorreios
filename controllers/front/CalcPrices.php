<?php
class AgCorreiosCalcPricesModuleFrontController extends ModuleFrontController
{
    public static $watchdog_delay = 120;
    public function initContent()
    {
        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agcorreios/logs/CalcPrices.log', 1);

        /** @var AgClienteWorker */
        $id_worker = Tools::getValue('id_agworker');

        global $agti_worker;
        $agti_worker = new AgClienteWorker($id_worker);
        $agti_worker->save();

        $options = $this->module->getOptions();
        if (!$options['agcorreios_precalculate']) {
            AgClienteLogger::addLog("agcorreios - Opção de pré-cálculo não ativada.", 2, null, null, null, true);
            exit();
        }
        
        if (!Validate::isLoadedObject($agti_worker)) {
            AgClienteLogger::addLog("agcorreios - Worker {$id_worker} não encontrado.", 3, null, null, null, true);
            exit();
        }

        set_time_limit(0);
        ignore_user_abort(true);


        AgClienteLogger::addLog(sprintf('iniciando atualização de preços'));
        AgCorreiosPrices::calcAll();
        AgClienteLogger::addLog(sprintf('fim da atualização de preços'));

        exit();
    }
}
