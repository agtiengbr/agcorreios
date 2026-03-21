<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Entity\AgCorreiosToken;
use AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem\AutenticaCartaoDePostagemService;
use AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem\AutenticaCartaoDePostagemServiceArgs;

class TokenRetriever
{
    private $apiService;
    public function __construct(AutenticaCartaoDePostagemService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function exec($user, $pass, $numeroCartao)
    {
        $args = new AutenticaCartaoDePostagemServiceArgs;
        $args->setNumero($numeroCartao);

        $r = $this->apiService->exec($args, $user, $pass);
        if ($this->apiService->getRequest()->getHttpCode() == 401) {
            throw new \Exception("Erro de autenticação. Confira as credenciais dos Correios.");
        }
        if ($r) {
            return (new AgCorreiosToken)
                ->setToken($r->getToken())
                ->setExpirationDate($r->getExpiraEm());
        }

        
        throw new \Exception("Erro obtendo o token de acesso com os Correios.");
    }
}