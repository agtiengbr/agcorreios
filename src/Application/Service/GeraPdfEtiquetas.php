<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Entity\AgCorreiosToken;
use AGTI\Correios\Entity\AgcorreiosTracking;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\ConsultaRotulo\ConsultaRotuloService;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarRotulo\CriarRotuloService;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarRotulo\CriarRotuloServiceArgs;
use AGTI\Correios\ValueObject\Configuration as VBConfiguration;

class GeraPdfEtiquetas
{
    /** @var CriarRotuloService */
    private $criarRotuloService;

    /** @var ConsultaRotuloService */
    private $consultaRotuloService;

    /** @var VBConfiguration */
    private $configuration;

    public function __construct($criarRotuloService, $consultaRotuloService, $configuration)
    {
        $this->criarRotuloService = $criarRotuloService;
        $this->consultaRotuloService = $consultaRotuloService;
        $this->configuration = $configuration;
    }

    /**
     * @param AgcorreiosTracking[] $trackings - Entidades que comporão o PDF
     * 
     * @return void 
     */
    public function exec($trackings, AgCorreiosToken $token)
    {
        $args = new CriarRotuloServiceArgs;
        $args->setCodigosObjeto(
            array_map(function(AgcorreiosTracking $tracking){
                return $tracking->getTrackingCode();
            }, $trackings)
        )
        ->setIdCorreios($this->configuration->getUsername())
        ->setNumeroCartaoPostagem($this->configuration->getCartaoPostagem());

        $idRecibo = $this->criarRotuloService->exec($args, $token->getToken());

        sleep(4);
        $r = $this->consultaRotuloService->exec($idRecibo, $token->getToken());
        
        $f = tmpfile();
        fwrite($f, base64_decode($r->getDados()));
        return $f;
    }
}