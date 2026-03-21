<?php
class agcorreioscreateintervalsModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();

        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agcorreios/logs/create_intervals.log', 1);

        if (!Tools::getValue('force')) {
            exit();
        }

        set_time_limit(0);
        ignore_user_abort(true);

        AgCorreiosInterval::createDefaultIntervals();
        exit();
    }
}