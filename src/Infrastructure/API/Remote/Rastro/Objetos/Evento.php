<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos;

class Evento
{
    private $codigo;
    private $tipo;
    private $dtHrCriado;
    private $descricao;
    private $unidade;

    /**
     * Get the value of codigo
     */ 
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @return  self
     */ 
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

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
     * Get the value of dtHrCriado
     */ 
    public function getDtHrCriado()
    {
        return $this->dtHrCriado;
    }

    /**
     * Set the value of dtHrCriado
     *
     * @return  self
     */ 
    public function setDtHrCriado(\DateTime $dtHrCriado)
    {
        $this->dtHrCriado = $dtHrCriado;

        return $this;
    }

    /**
     * Get the value of descricao
     */ 
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set the value of descricao
     *
     * @return  self
     */ 
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get the value of unidade
     */ 
    public function getUnidade()
    {
        return $this->unidade;
    }

    /**
     * Set the value of unidade
     *
     * @return  self
     */ 
    public function setUnidade(Unidade $unidade)
    {
        $this->unidade = $unidade;

        return $this;
    }
}