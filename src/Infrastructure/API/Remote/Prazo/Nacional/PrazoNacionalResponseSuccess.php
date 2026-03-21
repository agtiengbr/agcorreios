<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional;

class PrazoNacionalResponseSuccess
{
    private $coProduto;
    private $prazoEntrega;
    private $dataMaxima;
    private $entregaDomiciliar;
    private $entregaSabado;

    /**
     * Get the value of coProduto
     */ 
    public function getCoProduto()
    {
        return $this->coProduto;
    }

    /**
     * Set the value of coProduto
     *
     * @return  self
     */ 
    public function setCoProduto($coProduto)
    {
        $this->coProduto = $coProduto;

        return $this;
    }

    /**
     * Get the value of prazoEntrega
     */ 
    public function getPrazoEntrega()
    {
        return $this->prazoEntrega;
    }

    /**
     * Set the value of prazoEntrega
     *
     * @return  self
     */ 
    public function setPrazoEntrega($prazoEntrega)
    {
        $this->prazoEntrega = $prazoEntrega;

        return $this;
    }

    /**
     * Get the value of dataMaxima
     */ 
    public function getDataMaxima()
    {
        return $this->dataMaxima;
    }

    /**
     * Set the value of dataMaxima
     *
     * @return  self
     */ 
    public function setDataMaxima(\DateTime $dataMaxima)
    {
        $this->dataMaxima = $dataMaxima;

        return $this;
    }

    /**
     * Get the value of entregaDomiciliar
     */ 
    public function getEntregaDomiciliar()
    {
        return $this->entregaDomiciliar;
    }

    /**
     * Set the value of entregaDomiciliar
     *
     * @return  self
     */ 
    public function setEntregaDomiciliar($entregaDomiciliar)
    {
        $this->entregaDomiciliar = $entregaDomiciliar;

        return $this;
    }

    /**
     * Get the value of entregaSabado
     */ 
    public function getEntregaSabado()
    {
        return $this->entregaSabado;
    }

    /**
     * Set the value of entregaSabado
     *
     * @return  self
     */ 
    public function setEntregaSabado($entregaSabado)
    {
        $this->entregaSabado = $entregaSabado;

        return $this;
    }
}