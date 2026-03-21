<?php
namespace AGTI\Correios\Infrastructure\API\Remote\DataModel;

class CartaoDePostagem
{
    /** @var string */
    private $dr;

    /** @var string */
    private $contrato;

    /** @var string */
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
     * Get the value of dr
     */ 
    public function getDr()
    {
        return $this->dr;
    }

    /**
     * Set the value of dr
     *
     * @return  self
     */ 
    public function setDr($dr)
    {
        $this->dr = $dr;

        return $this;
    }
}