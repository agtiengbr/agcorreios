<?php

class AgCorreiosOrderTrackingModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $orderId = (int) Tools::getValue('id_order');
        $order = new Order($orderId);

        $orderCarrier = new OrderCarrier((int) $order->getIdOrderCarrier());
        $trackingInfo = Validate::isLoadedObject($orderCarrier)
            ? trim((string) $orderCarrier->tracking_number)
            : '';

        $trackingEvents = [];
        if ($trackingInfo !== '') {
            $trackingEvents = AgCorreiosTracking::normalizeTrackingEventsForDisplay(
                AgCorreiosTracking::getFullTrackingEvents($trackingInfo)
            );
        }

        $this->context->smarty->assign([
            'order' => $order,
            'trackingInfo' => $trackingInfo,
            'trackingEvents' => $trackingEvents,
        ]);

        $this->setTemplate('module:agcorreios/views/templates/front/rastreio.tpl');
    }
}
