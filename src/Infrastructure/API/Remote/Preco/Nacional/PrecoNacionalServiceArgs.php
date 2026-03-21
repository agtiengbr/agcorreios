<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional;

use AGTI\Correios\Entity\AgCorreiosToken;

class PrecoNacionalServiceArgs
{
    private $service;
    private $postcodeOrigin;
    private $postcodeTarget;
    private $psObjeto;
    private $tpObjeto;
    private $width;
    private $depth;
    private $height;
    private $token;
    private $contractNumber;
    private $drNumber;

    /**
     * Get the value of height
     */ 
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of height
     *
     * @return  self
     */ 
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the value of depth
     */ 
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set the value of depth
     *
     * @return  self
     */ 
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get the value of width
     */ 
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of width
     *
     * @return  self
     */ 
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the value of tpObjeto
     */ 
    public function getTpObjeto()
    {
        return $this->tpObjeto;
    }

    /**
     * Set the value of tpObjeto
     *
     * @return  self
     */ 
    public function setTpObjeto($tpObjeto)
    {
        $this->tpObjeto = $tpObjeto;

        return $this;
    }

    /**
     * Get the value of psObjeto
     */ 
    public function getPsObjeto()
    {
        return $this->psObjeto;
    }

    /**
     * Set the value of psObjeto
     *
     * @return  self
     */ 
    public function setPsObjeto($psObjeto)
    {
        $this->psObjeto = $psObjeto;

        return $this;
    }

    /**
     * Get the value of postcodeTarget
     */ 
    public function getPostcodeTarget()
    {
        return $this->postcodeTarget;
    }

    /**
     * Set the value of postcodeTarget
     *
     * @return  self
     */ 
    public function setPostcodeTarget($postcodeTarget)
    {
        $this->postcodeTarget = $postcodeTarget;

        return $this;
    }

    /**
     * Get the value of postcodeOrigin
     */ 
    public function getPostcodeOrigin()
    {
        return $this->postcodeOrigin;
    }

    /**
     * Set the value of postcodeOrigin
     *
     * @return  self
     */ 
    public function setPostcodeOrigin($postcodeOrigin)
    {
        $this->postcodeOrigin = $postcodeOrigin;

        return $this;
    }

    /**
     * Get the value of service
     */ 
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set the value of service
     *
     * @return  self
     */ 
    public function setService($service)
    {
        $this->service = $service;

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
    public function setToken(AgCorreiosToken $token)
    {
        $this->token = $token;

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
}