<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem;

use AGTI\Correios\Infrastructure\API\Remote\BaseService;

class AutenticaCartaoDePostagemServiceArgs
{
    private $numero;

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }
}