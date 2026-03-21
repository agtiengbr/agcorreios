<?php
namespace AGTI\Correios\ValueObject;

class Configuration
{
    private $username;
    private $password;
    private $cartaoPostagem;
    private $contractNumber;
    private $drNumber;

    /** @var string */
    private $cnpj;

    /** @var SenderData **/
    private $senderData;

    /** @var Mappings */
    private $mappings;

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
    public function setCartaoPostagem($cartaoPostagem)
    {
        $this->cartaoPostagem = $cartaoPostagem;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of drNumber
     */ 
    public function getDrNumber()
    {
        return $this->drNumber;
    }

    /**
     * Set the value of drNumber
     *
     * @return  self
     */ 
    public function setDrNumber($drNumber)
    {
        $this->drNumber = $drNumber;

        return $this;
    }

    /**
     * Get the value of contractNumber
     */ 
    public function getContractNumber()
    {
        return $this->contractNumber;
    }

    /**
     * Set the value of contractNumber
     *
     * @return  self
     */ 
    public function setContractNumber($contractNumber)
    {
        $this->contractNumber = $contractNumber;

        return $this;
    }

    /**
     * Get the value of senderData
     */ 
    public function getSenderData()
    {
        if ($this->senderData === null) {
            return new SenderData;
        }
        
        return $this->senderData;
    }

    /**
     * Set the value of senderData
     * 
     * @param SenderData
     *
     * @return  self
     */ 
    public function setSenderData(SenderData $senderData)
    {
        $this->senderData = $senderData;

        return $this;
    }

    /**
     * Get the value of mappings
     */ 
    public function getMappings()
    {
        if ($this->mappings === null) {
            $this->mappings = new Mappings;
        }
        
        return $this->mappings;
    }

    /**
     * Set the value of mappings
     *
     * @return  self
     */ 
    public function setMappings($mappings)
    {
        $this->mappings = $mappings;

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
}