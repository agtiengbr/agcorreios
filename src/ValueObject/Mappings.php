<?php
namespace AGTI\Correios\ValueObject;

use AGTI\Correios\Infrastructure\Mapping\AddressNumberMapping;
use AGTI\Correios\Infrastructure\Mapping\CNPJMapping;
use AGTI\Correios\Infrastructure\Mapping\CompanyNameMapping;
use AGTI\Correios\Infrastructure\Mapping\CPFMapping;
use AGTI\Correios\Infrastructure\Mapping\IEMapping;
use AGTI\Correios\Infrastructure\Mapping\RGMapping;

class Mappings
{
    private $cpf;
    private $rg;
    private $cnpj;
    private $ie;
    private $companyName;

    /** @var AddressNumberMapping */
    private $addressNumber;

    /**
     * Get the value of cpf
     */ 
    public function getCpf()
    {
        if (is_null($this->cpf)) {
            return new CPFMapping;
        }

        return $this->cpf;
    }

    /**
     * Set the value of cpf
     *
     * @return  self
     */ 
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get the value of rg
     */ 
    public function getRg()
    {
        if (is_null($this->rg)) {
            return new RGMapping;
        }

        return $this->rg;
    }

    /**
     * Set the value of rg
     *
     * @return  self
     */ 
    public function setRg($rg)
    {
        $this->rg = $rg;

        return $this;
    }

    /**
     * Get the value of cnpj
     */ 
    public function getCnpj()
    {
        if (is_null($this->cnpj)) {
            return new CNPJMapping;
        }

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
     * Get the value of ie
     */ 
    public function getIe()
    {
        if (is_null($this->ie)) {
            return new IEMapping;
        }

        return $this->ie;
    }

    /**
     * Set the value of ie
     *
     * @return  self
     */ 
    public function setIe($ie)
    {
        $this->ie = $ie;

        return $this;
    }

    /**
     * Get the value of companyName
     */ 
    public function getCompanyName()
    {
        if (is_null($this->companyName)) {
            return new CompanyNameMapping;
        }
        
        return $this->companyName;
    }

    /**
     * Set the value of companyName
     *
     * @return  self
     */ 
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get the value of addressNumber
     */ 
    public function getAddressNumber()
    {
        if (is_null($this->addressNumber)) {
            return new AddressNumberMapping;
        }
        
        return $this->addressNumber;
    }

    /**
     * Set the value of addressNumber
     *
     * @return  self
     */ 
    public function setAddressNumber($addressNumber)
    {
        $this->addressNumber = $addressNumber;

        return $this;
    }
}