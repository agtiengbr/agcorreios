<?php
namespace AGTI\Correios\Infrastructure\API\Remote\DataModel;

class ShippingService
{
    /** @var string */
    private $codigo;
    
    /** @var string */
    private $descricao;
    
    /** @var integer */
    private $coSegmento;
    
    /** @var string */
    private $descSegmento;

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
     * Get the value of coSegmento
     */ 
    public function getCoSegmento()
    {
        return $this->coSegmento;
    }

    /**
     * Set the value of coSegmento
     *
     * @return  self
     */ 
    public function setCoSegmento($coSegmento)
    {
        $this->coSegmento = $coSegmento;

        return $this;
    }

    /**
     * Get the value of descSegmento
     */ 
    public function getDescSegmento()
    {
        return $this->descSegmento;
    }

    /**
     * Set the value of descSegmento
     *
     * @return  self
     */ 
    public function setDescSegmento($descSegmento)
    {
        $this->descSegmento = $descSegmento;

        return $this;
    }
}