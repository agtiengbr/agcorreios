<?php
namespace AGTI\Correios\ValueObject;

class SenderData
{
    private $postcode;
    private $shopName;
    private $email;
    private $documentNumber;
    private $address;
    private $addressNumber;
    private $others;
    private $neighborhood;
    private $city;
    private $uf;
    private $ddd;
    private $cellphone;

    /**
     * Get the value of uf
     */ 
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * Set the value of uf
     *
     * @return  self
     */ 
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of neighborhood
     */ 
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * Set the value of neighborhood
     *
     * @return  self
     */ 
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * Get the value of others
     */ 
    public function getOthers()
    {
        return $this->others;
    }

    /**
     * Set the value of others
     *
     * @return  self
     */ 
    public function setOthers($others)
    {
        $this->others = $others;

        return $this;
    }

    /**
     * Get the value of addressNumber
     */ 
    public function getAddressNumber()
    {
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

    /**
     * Get the value of address
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @return  self
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of documentNumber
     */ 
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * Set the value of documentNumber
     *
     * @return  self
     */ 
    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of shopName
     */ 
    public function getShopName()
    {
        return $this->shopName;
    }

    /**
     * Set the value of shopName
     *
     * @return  self
     */ 
    public function setShopName($shopName)
    {
        $this->shopName = $shopName;

        return $this;
    }

    /**
     * Get the value of postcode
     */ 
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set the value of postcode
     *
     * @return  self
     */ 
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get the value of cellphone
     */ 
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set the value of cellphone
     *
     * @return  self
     */ 
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get the value of ddd
     */ 
    public function getDdd()
    {
        return $this->ddd;
    }

    /**
     * Set the value of ddd
     *
     * @return  self
     */ 
    public function setDdd($ddd)
    {
        $this->ddd = $ddd;

        return $this;
    }
}