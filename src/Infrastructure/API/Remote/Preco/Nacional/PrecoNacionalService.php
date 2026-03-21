<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional;

use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class PrecoNacionalService extends BaseService
{
    private $args;

    public function getApiEndpoint()
    {
        return "preco/v1/nacional/{$this->args->getService()}?cepOrigem={$this->args->getPostcodeOrigin()}&cepDestino={$this->args->getPostcodeTarget()}&psObjeto={$this->args->getPsObjeto()}&tpObjeto={$this->args->getTpObjeto()}&comprimento={$this->args->getWidth()}&largura={$this->args->getDepth()}&altura={$this->args->getHeight()}&nuContrato={$this->args->getContractNumber()}&nuDR={$this->args->getDrNumber()}";
    }

    public function exec(PrecoNacionalServiceArgs $args)
    {
        $this->args = $args;

        $r = $this->send("GET", "", [], ['Authorization: Bearer ' . $args->getToken()->getToken()]);
        
        if ($this->getRequest()->getHttpCode() == 200) {
            $r = $this->getSerializer()->deserialize(
                $this->getRequest()->getResponse(),
                PrecoNacionalResponseSuccess::class,
                'json'
            );

            return $r;
        }

        if ($this->getRequest()->getHttpCode() == 400) {
            $r = $this->getSerializer()->deserialize(
                $this->getRequest()->getResponse(),
                PrecoNacionalResponseError::class,
                'json'
            );
            
            return $r;
        }
    }
}