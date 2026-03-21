<?php
namespace AGTI\Correios\Infrastructure\API\Remote\Contrato;

use AGTI\Correios\Entity\AgCorreiosToken;
use AGTI\Correios\Infrastructure\API\Remote\DataModel\Pageing;
use AGTI\Correios\Infrastructure\API\Remote\DataModel\ShippingService;

class ContratoServiceResponseSuccess
{
    /** @var ShippingService[] */
    private $itens;

    /**
     * @var Pageing
     **/
    private $page;

    /**
     * Get the value of page
     */ 
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the value of page
     * 
     * @param Pageing $page
     *
     * @return  self
     */ 
    public function setPage(Pageing $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of itens
     */ 
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * Set the value of itens
     *
     * @return  self
     */ 
    public function setItens($itens)
    {
        $this->itens = $itens;

        return $this;
    }
}