<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos;

class Objeto
{
    private $codObjeto;
    private $tipoPostal;
    private $dtPrevista;
    private $contrato;
    private $largura;
    private $comprimento;
    private $altura;
    private $peso;
    private $formato;
    private $modalidade;
    private $valorDeclarado;
    private $eventos;

    /**
     * Get the value of peso
     */ 
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set the value of peso
     *
     * @return  self
     */ 
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get the value of formato
     */ 
    public function getFormato()
    {
        return $this->formato;
    }

    /**
     * Set the value of formato
     *
     * @return  self
     */ 
    public function setFormato($formato)
    {
        $this->formato = $formato;

        return $this;
    }

    /**
     * Get the value of modalidade
     */ 
    public function getModalidade()
    {
        return $this->modalidade;
    }

    /**
     * Set the value of modalidade
     *
     * @return  self
     */ 
    public function setModalidade($modalidade)
    {
        $this->modalidade = $modalidade;

        return $this;
    }

    /**
     * Get the value of valorDeclarado
     */ 
    public function getValorDeclarado()
    {
        return $this->valorDeclarado;
    }

    /**
     * Set the value of valorDeclarado
     *
     * @return  self
     */ 
    public function setValorDeclarado($valorDeclarado)
    {
        $this->valorDeclarado = $valorDeclarado;

        return $this;
    }

    /**
     * Get the value of eventos
     */ 
    public function getEventos()
    {
        return $this->eventos;
    }

    /**
     * Set the value of eventos
     *
     * @return  self
     */ 
    public function setEventos($eventos)
    {
        $this->eventos = $eventos;

        return $this;
    }

    /**
     * Get the value of altura
     */ 
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * Set the value of altura
     *
     * @return  self
     */ 
    public function setAltura($altura)
    {
        $this->altura = $altura;

        return $this;
    }

    /**
     * Get the value of comprimento
     */ 
    public function getComprimento()
    {
        return $this->comprimento;
    }

    /**
     * Set the value of comprimento
     *
     * @return  self
     */ 
    public function setComprimento($comprimento)
    {
        $this->comprimento = $comprimento;

        return $this;
    }

    /**
     * Get the value of largura
     */ 
    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * Set the value of largura
     *
     * @return  self
     */ 
    public function setLargura($largura)
    {
        $this->largura = $largura;

        return $this;
    }

    /**
     * Get the value of contrato
     */ 
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * Set the value of contrato
     *
     * @return  self
     */ 
    public function setContrato($contrato)
    {
        $this->contrato = $contrato;

        return $this;
    }

    /**
     * Get the value of dtPrevista
     */ 
    public function getDtPrevista()
    {
        return $this->dtPrevista;
    }

    /**
     * Set the value of dtPrevista
     *
     * @return  self
     */ 
    public function setDtPrevista($dtPrevista)
    {
        $this->dtPrevista = $dtPrevista;

        return $this;
    }

    /**
     * Get the value of tipoPostal
     */ 
    public function getTipoPostal()
    {
        return $this->tipoPostal;
    }

    /**
     * Set the value of tipoPostal
     *
     * @return  self
     */ 
    public function setTipoPostal(TipoPostal $tipoPostal)
    {
        $this->tipoPostal = $tipoPostal;

        return $this;
    }

    /**
     * Get the value of codObjeto
     */ 
    public function getCodObjeto()
    {
        return $this->codObjeto;
    }

    /**
     * Set the value of codObjeto
     *
     * @return  self
     */ 
    public function setCodObjeto($codObjeto)
    {
        $this->codObjeto = $codObjeto;

        return $this;
    }
}