<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem;

use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class AutenticaCartaoDePostagemService extends BaseService
{

    public function getApiEndpoint()
    {
        return "token/v1/autentica/cartaopostagem";
    }
    
    public function exec(AutenticaCartaoDePostagemServiceArgs $args, $username, $password)
    {
        $this->send(
            'POST',
            $this->getSerializer()->serialize($args, 'json'),
            [],
            [
                'Authorization: Basic ' . base64_encode("{$username}:{$password}"),
                'Content-Type: application/json'
            ]
        );
        if ($this->getRequest()->getHttpCode() == 201) {
            /** @var AutenticaCartaoDePostagemResponseSuccess */
            $r = $this->getSerializer()->deserialize(
                $this->getRequest()->getResponse(),
                AutenticaCartaoDePostagemResponseSuccess::class,
                'json'
            );

            return $r;
        }
    }
}