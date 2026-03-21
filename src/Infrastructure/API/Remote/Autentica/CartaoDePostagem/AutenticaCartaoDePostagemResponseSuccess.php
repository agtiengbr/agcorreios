<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem;

use AGTI\Correios\Infrastructure\API\Remote\DataModel\CartaoDePostagem;

class AutenticaCartaoDePostagemResponseSuccess
{
    private $ambiente;
    private $id;
    private $ip;
    private $perfil;
    private $cnpj;
    private $emissao;
    private $expiraEm;
    private $zoneOffset;
    private $token;

    /** @var CartaoDePostagem */
    private $cartaoPostagem;

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
     * Get the value of zoneOffset
     */ 
    public function getZoneOffset()
    {
        return $this->zoneOffset;
    }

    /**
     * Set the value of zoneOffset
     *
     * @return  self
     */ 
    public function setZoneOffset($zoneOffset)
    {
        $this->zoneOffset = $zoneOffset;

        return $this;
    }

    /**
     * Get the value of expiraEm
     */ 
    public function getExpiraEm()
    {
        return $this->expiraEm;
    }

    /**
     * Set the value of expiraEm
     *
     * @return  self
     */ 
    public function setExpiraEm(\DateTime $expiraEm)
    {
        $this->expiraEm = $expiraEm;

        return $this;
    }

    /**
     * Get the value of emissao
     */ 
    public function getEmissao()
    {
        return $this->emissao;
    }

    /**
     * Set the value of emissao
     *
     * @return  self
     */ 
    public function setEmissao(\DateTime $emissao)
    {
        $this->emissao = $emissao;

        return $this;
    }

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
     * Get the value of perfil
     */ 
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set the value of perfil
     *
     * @return  self
     */ 
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get the value of ip
     */ 
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set the value of ip
     *
     * @return  self
     */ 
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of ambiente
     */ 
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * Set the value of ambiente
     *
     * @return  self
     */ 
    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;

        return $this;
    }

    /**
     * Get the value of cartaoPostagem
     */ 
    public function getCartaoPostagem()
    {
        return $this->cartaoPostagem;
    }

    /**
     * Set the value of cartaoPostagem
     *
     * @return  self
     */ 
    public function setCartaoPostagem(CartaoDePostagem $cartaoPostagem)
    {
        $this->cartaoPostagem = $cartaoPostagem;

        return $this;
    }
}