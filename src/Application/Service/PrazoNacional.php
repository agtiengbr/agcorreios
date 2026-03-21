<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Entity\AgCorreiosToken;
use AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional\PrazoNacionalResponseError;
use AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional\PrazoNacionalResponseSuccess;
use AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional\PrazoNacionalService;
use AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional\PrazoNacionalServiceArgs;

class PrazoNacional
{
    private $apiService;
    public function __construct(PrazoNacionalService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function exec(
        $service,
        $postcodeOrigin,
        $postcodeTarget,
        $token
    )
    {
        $args = new PrazoNacionalServiceArgs;

        $args->setService($service);
        $args->setPostcodeOrigin($postcodeOrigin);
        $args->setPostcodeTarget($postcodeTarget);
        $args->setToken($token);

        $r = $this->apiService->exec($args);
    
        if ($r instanceof PrazoNacionalResponseSuccess) {
            return $r;
        }

        if ($r instanceof PrazoNacionalResponseError) {
            throw new \Exception("Erro obtendo o prazo de entrega - " . implode("\n", $r->getMsgs()));
        }

        throw new \Exception("Erro obtendo o prazo de entrega.");
    }
}