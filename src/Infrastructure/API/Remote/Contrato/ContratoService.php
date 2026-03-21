<?php
namespace AGTI\Correios\Infrastructure\API\Remote\Contrato;

use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class ContratoService extends BaseService
{
    /** @var ContratoServiceArgs */
    private $args;

    public function getApiEndpoint()
    {
        return "meucontrato/v1/empresas/{$this->args->getCnpj()}/contratos/{$this->args->getNumeroContrato()}/servicos";
    }

    /**
     * @return ContratoServiceResponseSuccess
     */
    public function exec(ContratoServiceArgs $args)
    {
        $this->args = $args;

        $this->send(
            "GET",
            "",
            [
                "nuCartaoPostagem" => $this->args->getNumeroCartao(),
                "size" => $this->args->getSize(),
                "page" => $this->args->getPage()
            ],
            [
                'Authorization: Bearer ' . $args->getToken()->getToken()
            ]
        );

        $ret = $this->getSerializer()->deserialize($this->getRequest()->getResponse(), ContratoServiceResponseSuccess::class, 'json');

        return $ret;
    }
}