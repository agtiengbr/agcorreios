<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional;

use AGTI\Correios\Entity\AgCorreiosToken;

class PrazoNacionalServiceArgs
{
    private $service;
    private $postcodeOrigin;
    private $postcodeTarget;
    private $token;

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
}