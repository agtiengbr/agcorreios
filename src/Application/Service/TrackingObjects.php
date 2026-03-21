<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Entity\AgCorreiosToken;
use AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\RastrearObjetosService;
use AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\RastrearObjetosServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\RastrearObjetosResponseSuccess;


use AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\Evento;
class TrackingObjects
{
    private $apiService;
    public function __construct(RastrearObjetosService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function exec($objetos,$token)
    {

        $args = new RastrearObjetosServiceArgs;
        foreach ($objetos as $objeto) {
            $args->addObjeto($objeto->tracking_code);
        }
        $args->setToken($token);

        $r = $this->apiService->exec($args);

        if ($r instanceof RastrearObjetosResponseSuccess) {
            return $r;
        }

        throw new \Exception("Erro realizando ao rastrear objeto - " . implode("\n", $r->getMsgs()));
    }
}