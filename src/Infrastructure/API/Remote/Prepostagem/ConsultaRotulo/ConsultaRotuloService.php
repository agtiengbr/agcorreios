<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\ConsultaRotulo;

use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class ConsultaRotuloService extends BaseService
{
    private $idRecibo;

    public function getApiEndpoint()
    {
        return "prepostagem/v1/prepostagens/rotulo/download/assincrono/{$this->idRecibo}";
    }

    public function exec($idRecibo, $token)
    {
        $this->idRecibo = $idRecibo;

        $this->send("GET", "", [], ["Authorization: Bearer " . $token]);

        if ($this->getRequest()->getHttpCode() == '200') {
            $r = $this->getSerializer()->deserialize($this->getRequest()->getResponse(), ConsultaRotuloResponseSuccess::class, 'json');
            return $r;
        }

        dump($this->getRequest());
        exit();
    }
    
}