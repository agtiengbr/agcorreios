<?php
use AGTI\Correios\Application\Service\TrackingObjects;

class agcorreiosViewrastreioModuleFrontController extends ModuleFrontController
{

    public function initContent() {
        parent::initContent();

        $trackingNumber = (Tools::getValue('tracking_number'));

        $this->context->smarty->assign(['tracking' => AgCorreiosTracking::getFullTrackingEvents($trackingNumber)]);
        return $this->setTemplate('module:agcorreios/views/templates/front/rastreio.tpl'); 

    }

}