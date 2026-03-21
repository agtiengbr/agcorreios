<?php
namespace AGTI\Correios\Application\Exception;

use AGTI\Correios\Entity\AgCorreiosApiRequest;

class ApiException extends \Exception
{
    /** @var AgCorreiosApiRequest */
    private $apiRequest;

    /** @var string */
    private $apiMessage;

    /**
     * Get the value of apiRequest
     */ 
    public function getApiRequest()
    {
        return $this->apiRequest;
    }

    /**
     * Set the value of apiRequest
     *
     * @return  self
     */ 
    public function setApiRequest($apiRequest)
    {
        $this->apiRequest = $apiRequest;

        return $this;
    }

    /**
     * Get the value of apiMessage
     */ 
    public function getApiMessage()
    {
        return $this->apiMessage;
    }

    /**
     * Set the value of apiMessage
     *
     * @return  self
     */ 
    public function setApiMessage($apiMessage)
    {
        $this->apiMessage = $apiMessage;

        return $this;
    }
}