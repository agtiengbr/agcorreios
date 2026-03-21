<?php
class AgCorreiosCalcPriceModuleFrontController extends ModuleFrontController
{
    public static $watchdog_delay = 120;
    public function initContent()
    {
        $id = Tools::getValue('id_agcorreios_price');
        $obj = new AgCorreiosPrices($id);

        if (!Validate::isLoadedObject($obj)) {
            echo 'Faixa de Preço não encontrada.';
            exit();
        }

        if (!Tools::getValue('debug')) {
            $obj = new AgCorreiosPrices($id);
            $obj->calcAndUpdate();
            $obj = new AgCorreiosPrices($id);
        } else {
            $obj = new AgCorreiosPrices($id);

            $options = $this->module->getOptions();
            $service = new AgCorreiosServices($obj->id_agcorreios_service);
            
            AgCorreiosPrices::calcExternal(
                $service->correios_code,
                $options['agcorreios_zipcode_origin'],
                $obj->zipcode,
                $obj->weight,
                2,
                20,
                20,
                0,
                false,
                false,
                true,
                true,
                $options['agcorreios_contract_number'],
                $options['agcorreios_contract_password']
            );
        }

        echo 'Custo do frete: ' . $obj->shipping_cost;
        exit();
    }
}
