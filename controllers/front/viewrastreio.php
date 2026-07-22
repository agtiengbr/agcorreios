<?php

class agcorreiosViewrastreioModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $trackingNumber = trim((string) (Tools::getValue('tracking_number') ?: Tools::getValue('objeto')));
        $trackingEvents = [];

        if ($trackingNumber !== '') {
            $trackingEvents = AgCorreiosTracking::normalizeTrackingEventsForDisplay(
                AgCorreiosTracking::getFullTrackingEvents($trackingNumber)
            );
        }

        $this->context->smarty->assign([
            'trackingInfo' => $trackingNumber,
            'trackingEvents' => $trackingEvents,
        ]);

        return $this->setTemplate('module:agcorreios/views/templates/front/rastreio.tpl');
    }
}
