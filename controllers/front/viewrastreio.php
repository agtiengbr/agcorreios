<?php

class agcorreiosViewrastreioModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $trackingNumber = Tools::getValue('tracking_number') ?: Tools::getValue('objeto');
        $trackingEvents = [];

        if ($trackingNumber) {
            $trackingEvents = AgCorreiosTracking::getFullTrackingEvents($trackingNumber) ?: [];
            foreach ($trackingEvents as &$event) {
                if (!isset($event['date_add']) && isset($event['created'])) {
                    $event['date_add'] = $event['created'];
                }
            }
            unset($event);
        }

        $this->context->smarty->assign([
            'trackingInfo' => $trackingNumber,
            'trackingEvents' => $trackingEvents,
        ]);

        return $this->setTemplate('module:agcorreios/views/templates/front/rastreio.tpl');
    }
}
