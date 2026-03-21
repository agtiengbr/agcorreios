<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Entity\AgCorreiosToken;
use AGTI\Correios\Entity\AgcorreiosTracking;
use AGTI\Correios\Entity\Orders;
use AGTI\Correios\Infrastructure\API\Remote\Contrato\ContratoService;
use AGTI\Correios\Infrastructure\API\Remote\Contrato\ContratoServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\DataModel\ShippingService;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\Contato;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\CriarPrepostagemService;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\CriarPrepostagemServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\DeclaracaoConteudoItem;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\Endereco;
use AGTI\Correios\Infrastructure\Mapping\MappingAdapter;
use AGTI\Correios\ValueObject\Configuration;
use Doctrine\ORM\EntityManagerInterface;

class BuscaServicos
{
    use ApiApplicationTrait;

    /** @var ContratoService */
    private $apiService;

    /** @var Configuration */
    private $configuration;

    public function __construct(ContratoService $apiService, Configuration $configuration)
    {
        $this->apiService = $apiService;
        $this->configuration = $configuration;
    }

    /**
     * @return ShippingService[]
     */
    public function exec(AgCorreiosToken $token)
    {
        $return = [];
        $page = 0;

        $args = new ContratoServiceArgs;
        $args->setCnpj($this->configuration->getCnpj())
                ->setNumeroContrato($this->configuration->getContractNumber())
                ->setNumeroCartao($this->configuration->getCartaoPostagem())
                ->setToken($token);

        while(1) {
            $args->setPage($page);

            $r = $this->apiService->exec($args);
            if (count($r->getItens()) == 0) {
                break;
            }
            $page++;

            $return = array_merge($return, $r->getItens());
        }
        
        return $return;
    }
}