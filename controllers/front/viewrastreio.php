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

        $trackingRefreshFailed = false;
        $options = $this->module->getOptions();
        if ($trackingNumber !== '' && isset($options['AGCORREIOS_TRACKING_REALTIME']) && (string) $options['AGCORREIOS_TRACKING_REALTIME'] === '1') {
            try {
                require_once dirname(__DIR__, 2) . '/classes/AgCorreiosTrackingRefresh.php';
                $trackingEvents = (new AgCorreiosTrackingRefresh())->exec($this->module, $trackingNumber);
            } catch (\Throwable $e) {
                // Keep the previously loaded history when the remote refresh fails.
                $trackingRefreshFailed = true;
            }
        }

        $this->context->smarty->assign([
            'trackingInfo' => $trackingNumber,
            'trackingEvents' => $trackingEvents,
            'trackingRefreshFailed' => $trackingRefreshFailed,
        ]);

        return $this->setTemplate('module:agcorreios/views/templates/front/rastreio.tpl');
    }
}
