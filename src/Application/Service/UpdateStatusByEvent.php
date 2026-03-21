<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\Evento;

class UpdateStatusByEvent
{
    public function exec(Evento $evento,\AgCorreiosTracking $tracking)
    {


        $module = new \agcorreios;

        $options = $module->getOptions();

        if (!isset($options['AGCORREIOS_TRACKING_EVENT_'.$module->formatString($evento->getDescricao())])) {
            return;
        }
        $state = $options['AGCORREIOS_TRACKING_EVENT_'.$module->formatString($evento->getDescricao())];
        $order = new \Order($tracking->id_order);
        if (!\Validate::isLoadedObject($order)) {
            return;
        }
         
        $StatesFinished = explode(', ',$options['AGCORREIOS_TRACKING_EVENT_FINISHED']);
        
        if(in_array($evento->getDescricao(),$StatesFinished)){
            $tracking->finished = 1;
            $tracking->save();
        }

        if ($state != -1 && $state != null) {
            $history = $order->getHistory($order->id_lang, $state);
            if (count($history) == 0) {
                $order->setCurrentState($state);
            }
        }
    }
}