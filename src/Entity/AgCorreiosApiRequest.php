<?php

namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AgCorreiosApiRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column
    */
    private $id;

    /**
     * @ORM\Column(type="string")
     **/
    private $endpoint = null;

    /**
     * @ORM\Column(type="array")
     **/
    private $headers;

    /**
     * @ORM\Column(type="string")
     */
    private $method = null;

    /**
     * @ORM\Column(type="array")
     */
    private $body = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $httpCode = null;

    /**
     * @ORM\Column(type="string")
     */
    private $response = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdd = null;

    /**
     * @ORM\Column(type="float")
     */
    private $timeSpent = null;

    /**
     * @ORM\Column(type="text", name="stack_trace")
     */
    private $stack = null;

    /**
     * Get the value of response
     */ 
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set the value of response
     *
     * @return  self
     */ 
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get the value of dateAdd
     */ 
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set the value of dateAdd
     *
     * @return  self
     */ 
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get the value of timeSpent
     */ 
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * Set the value of timeSpent
     *
     * @return  self
     */ 
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;

        return $this;
    }

    /**
     * Get the value of httpCode
     */ 
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Set the value of httpCode
     *
     * @return  self
     */ 
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    /**
     * Get the value of body
     */ 
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the value of body
     *
     * @return  self
     */ 
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the value of method
     */ 
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the value of method
     *
     * @return  self
     */ 
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get the value of headers
     */ 
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the value of headers
     *
     * @return  self
     */ 
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the value of endpoint
     */ 
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the value of endpoint
     *
     * @return  self
     */ 
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

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
     * Get the value of stack
     */ 
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Set the value of stack
     *
     * @return  self
     */ 
    public function setStack($stack)
    {
        $this->stack = $stack;

        return $this;
    }
}