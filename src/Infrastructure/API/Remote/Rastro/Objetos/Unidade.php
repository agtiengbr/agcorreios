<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos;

class Unidade
{
    private $codSro;
    private $tipo;
    private $endereco;


    /**
     * Get the value of endereco
     */ 
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set the value of endereco
     *
     * @return  self
     */ 
    public function setEndereco(Endereco $endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of codSro
     */ 
    public function getCodSro()
    {
        return $this->codSro;
    }

    /**
     * Set the value of codSro
     *
     * @return  self
     */ 
    public function setCodSro($codSro)
    {
        $this->codSro = $codSro;

        return $this;
    }
}