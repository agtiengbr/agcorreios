<?php
namespace AGTI\Correios\Infrastructure\API\Remote\Contrato;

use AGTI\Correios\Entity\AgCorreiosToken;

class ContratoServiceArgs
{
    /** @var string */
    private $cnpj;

    /** @var string */
    private $numeroContrato;

    /** @var string */
    private $numeroCartao;

    /** @var AgCorreiosToken */
    private $token;

    /** @var integer */
    private $size = 50;

    /** @var integer */
    private $page = 1;

    /**
     * Get the value of cnpj
     */ 
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Set the value of cnpj
     *
     * @return  self
     */ 
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

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
     * @return  self
     */ 
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of size
     */ 
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @return  self
     */ 
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of numeroContrato
     */ 
    public function getNumeroContrato()
    {
        return $this->numeroContrato;
    }

    /**
     * Set the value of numeroContrato
     *
     * @return  self
     */ 
    public function setNumeroContrato($numeroContrato)
    {
        $this->numeroContrato = $numeroContrato;

        return $this;
    }

    /**
     * Get the value of numeroCartao
     */ 
    public function getNumeroCartao()
    {
        return $this->numeroCartao;
    }

    /**
     * Set the value of numeroCartao
     *
     * @return  self
     */ 
    public function setNumeroCartao($numeroCartao)
    {
        $this->numeroCartao = $numeroCartao;

        return $this;
    }
}