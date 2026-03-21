<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Entity\AgCorreiosToken;
use AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional\PrecoNacionalResponseError;
use AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional\PrecoNacionalResponseSuccess;
use AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional\PrecoNacionalService;
use AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional\PrecoNacionalServiceArgs;

class PrecoNacional
{
    private $apiService;
    public function __construct(PrecoNacionalService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function exec(
        $service,
        $postcodeOrigin,
        $postcodeTarget,
        $width,
        $depth,
        $height,
        $weight,
        $token,
        $contractNumber,
        $drNumber
    )
    {
        $args = new PrecoNacionalServiceArgs;

        $args->setService($service);
        $args->setPostcodeOrigin($postcodeOrigin);
        $args->setPostcodeTarget($postcodeTarget);
        $args->setPsObjeto($weight);
        $args->setTpObjeto(2);
        $args->setWidth($width);
        $args->setDepth($depth);
        $args->setHeight($height);
        $args->setToken($token);
        $args->setContractNumber($contractNumber);
        $args->setDrNumber($drNumber);

        $r = $this->apiService->exec($args);
        if ($r instanceof PrecoNacionalResponseSuccess) {
            $r->setPcBase(str_replace(',', '.', $r->getPcBase()));
            $r->setPcBaseGeral(str_replace(',', '.', $r->getPcBaseGeral()));
            $r->setVlBaseCalculoImposto(str_replace(',', '.', $r->getVlBaseCalculoImposto()));
            $r->setPeAdValorem(str_replace(',', '.', $r->getPeAdValorem()));
            $r->setVlSeguroAutomatico(str_replace(',', '.', $r->getVlSeguroAutomatico()));
            $r->setPeAdValorem(str_replace(',', '.', $r->getPeAdValorem()));
            $r->setPcFaixa(str_replace(',', '.', $r->getPcFaixa()));
            $r->setPcFaixaVariacao(str_replace(',', '.', $r->getPcFaixaVariacao()));
            $r->setPcProduto(str_replace(',', '.', $r->getPcProduto()));
            $r->setPcFinal(str_replace(',', '.', $r->getPcFinal()));
            
            return $r;
        }

        if ($r instanceof PrecoNacionalResponseError) {
            throw new \Exception("Erro realizando a cotação do frete - " . implode("\n", $r->getMsgs()));
        }

        throw new \Exception("Erro realizando a cotação do frete");
    }
}