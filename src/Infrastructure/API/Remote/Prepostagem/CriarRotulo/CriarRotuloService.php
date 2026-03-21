<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarRotulo;

use AGTI\Correios\Entity\NgCorreiosPrepostagem;
use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class CriarRotuloService extends BaseService
{

    public function getApiEndpoint()
    {
        return "prepostagem/v1/prepostagens/rotulo/assincrono/pdf";
    }

    public function exec(CriarRotuloServiceArgs $args, $token)
    {
        $this->send(
            "POST",
            $this->getSerializer()->serialize($args, "json"),
            [],
            [
                "Authorization: Bearer " . $token
            ]
        );
        
        if ($this->getRequest()->getHttpCode() == 200) {
            $dt = json_decode($this->getRequest()->getResponse());
            return $dt->idRecibo;
        }
    }
    
}