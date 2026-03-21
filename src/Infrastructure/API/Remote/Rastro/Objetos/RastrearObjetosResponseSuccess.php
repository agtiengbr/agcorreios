<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos;

class RastrearObjetosResponseSuccess
{
    private $versao;
    private $quantidade;
    private $objetos;
    private $tipoResultado;

    /**
     * Get the value of tipoResultado
     */ 
    public function getTipoResultado()
    {
        return $this->tipoResultado;
    }

    /**
     * Set the value of tipoResultado
     *
     * @return  self
     */ 
    public function setTipoResultado($tipoResultado)
    {
        $this->tipoResultado = $tipoResultado;

        return $this;
    }

    /**
     * Get the value of objetos
     */ 
    public function getObjetos()
    {
        return $this->objetos;
    }

    /**
     * Set the value of objetos
     *
     * @return  self
     */ 
    public function setObjetos($objetos)
    {
        $this->objetos = $objetos;

        return $this;
    }

    /**
     * Get the value of quantidade
     */ 
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set the value of quantidade
     *
     * @return  self
     */ 
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get the value of versao
     */ 
    public function getVersao()
    {
        return $this->versao;
    }

    /**
     * Set the value of versao
     *
     * @return  self
     */ 
    public function setVersao($versao)
    {
        $this->versao = $versao;

        return $this;
    }
}