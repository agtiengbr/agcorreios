<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos;

class RastrearObjetosServiceArgs
{
    private $objetos = [];
    private $resultado;
    private $token;


    /**
     * Get the value of resultado
     */ 
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Set the value of resultado
     *
     * @return  self
     */ 
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get the value of objetos
     */ 
    public function getObjetos()
    {
        return $this->objetos;
    }

    public function addObjeto($objeto)
    {
        $this->objetos[] = $objeto;
        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}