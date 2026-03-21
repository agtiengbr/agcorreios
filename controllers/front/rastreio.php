<?php

use AGTI\Correios\Application\Service\TrackingObjects;
use AGTI\Correios\Application\Service\TokenRetriever;
use AGTI\Correios\Application\Service\UpdateStatusByEvent;

use AGTI\Correios\ValueObject\Configuration as VBConfiguration;
use AGTI\Correios\ValueObject\Configuration;

class agcorreiosRastreioModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        set_time_limit(0);
        ignore_user_abort(true);
        
        try {
            parent::initContent();
            /** @var AgClienteWorker */
            $id_worker = Tools::getValue('id_agworker');

            global $agti_worker;
            $agti_worker = new AgClienteWorker($id_worker);

            $objetos = AgCorreiosTracking::getAllToBeTracked();
            $objetosPages = $this->paginateArray($objetos,50);

            foreach ($objetosPages as $objetos) {
                $module = new agcorreios;
                $config = $module->get(VBConfiguration::class);
                $serviceToken = $module->get(TokenRetriever::class);
                
                $token = $serviceToken->exec(
                    $config->getUsername(),
                    $config->getPassword(),
                    $config->getCartaoPostagem()
                );
                $service = $module->get(TrackingObjects::class);
                $rastreio = $service->exec($objetos,$token);
        
                foreach ($rastreio->getObjetos() as $objeto) {
                    $agCorreiosTracking = AgCorreiosTracking::getByTrackingCode($objeto->getCodObjeto());
                    foreach (array_reverse($objeto->getEventos()) as $evento) {

                        $agCorreiosUnity = AgCorreiosUnity::get($evento->getUnidade());
                        // se nao existir a unidade 
                        if($agCorreiosUnity->id_agcorreios_unity == null){
                            $agCorreiosUnity->cod_sro = $evento->getUnidade()->getCodSro();
                            $agCorreiosUnity->type = $evento->getUnidade()->getTipo();

                            $agCorreiosUnity->city = $evento->getUnidade()->getEndereco()->getCidade();
                            $agCorreiosUnity->state = $evento->getUnidade()->getEndereco()->getUf();

                            $agCorreiosUnity->save();
                        }
                    

                        $tracking = AgCorreiosTrackingEvents::get($agCorreiosTracking->id_agcorreios_tracking,$evento->getCodigo(),$evento->getTipo(),$agCorreiosUnity->id);
                        if($tracking->id == null){
                            $tracking = new AgCorreiosTrackingEvents();
                            $tracking->id_agcorreios_tracking = $agCorreiosTracking->id;
                            $tracking->id_agcorreios_unity = $agCorreiosUnity->id;
                            $tracking->code = $evento->getCodigo();
                            $tracking->type = $evento->getTipo();
                            $tracking->created = $evento->getDtHrCriado()->format('Y-m-d H:i:s');
                            $tracking->desc = $evento->getDescricao();
                            $tracking->save();
                        }
                        $service = $module->get(UpdateStatusByEvent::class);
                    $service->exec($evento,$agCorreiosTracking);
                    }
                }
            }
            // $agti_worker->save();
        } catch (Exception $e) {
            dump($e);
        }

        exit();
    }


    public function paginateArray($array, $chunkSize){
        $result = [];
        for ($i = 0; $i < count($array); $i += $chunkSize) {
            $result[] = array_slice($array, $i, $chunkSize);
        }
        return $result;
    }
}