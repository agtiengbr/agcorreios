<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional;

use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class PrazoNacionalService extends BaseService
{
    private $args;

    public function getApiEndpoint()
    {
        return "prazo/v1/nacional/{$this->args->getService()}?cepOrigem={$this->args->getPostcodeOrigin()}&cepDestino={$this->args->getPostcodeTarget()}";
    }

    public function exec(PrazoNacionalServiceArgs $args)
    {
        $this->args = $args;

        $r = $this->send("GET", "", [], ['Authorization: Bearer ' . $args->getToken()->getToken()]);
        
        if ($this->getRequest()->getHttpCode() == 200) {
            $r = $this->getSerializer()->deserialize(
                $this->getRequest()->getResponse(),
                PrazoNacionalResponseSuccess::class,
                'json'
            );

            return $r;
        }

        if ($this->getRequest()->getHttpCode() == 400) {
            $r = $this->getSerializer()->deserialize(
                $this->getRequest()->getResponse(),
                PrazoNacionalResponseError::class,
                'json'
            );
            
            return $r;
        }
    }
}