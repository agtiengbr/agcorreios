<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem;

use AGTI\Correios\Entity\AgcorreiosTracking;
use AGTI\Correios\Entity\NgCorreiosPrepostagem;
use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class CriarPrepostagemService extends BaseService
{

    public function getApiEndpoint()
    {
        return "prepostagem/v1/prepostagens";
    }

    /**
     * @return CriarPrepostagemErrorResponse|CriarPrepostagemServiceArgs 
     */
    public function exec(CriarPrePostagemServiceArgs $args, $token)
    {
        $r = $this->send(
            "POST",
            $this->getSerializer()->serialize($args, "json"),
            [],
            [
                "Authorization: Bearer " . $token
            ]
        );
        
        if ($this->getRequest()->getHttpCode() == 200) {
            /** @var CriarPrepostagemServiceArgs */
            $ret = $this->getSerializer()->deserialize($this->getRequest()->getResponse(), CriarPrepostagemServiceArgs::class, "json");
            return $ret;
        }

        /** @var CriarPrepostagemErrorResponse */
        $ret = $this->getSerializer()->deserialize($this->getRequest()->getResponse(), CriarPrepostagemErrorResponse::class, "json");
        return $ret;
    }
    
}