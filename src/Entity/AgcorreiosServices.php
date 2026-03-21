<?php

namespace AGTI\Correios\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AgcorreiosServices
{
    /**
     * @var int 
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_agcorreios_services", type="integer")
     */
    private $id;
    
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $correiosName;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $correiosCode;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $enabled;


    /**
     * Get the value of correiosCode
     *
     * @return  string
     */ 
    public function getCorreiosCode()
    {
        return $this->correiosCode;
    }

    /**
     * Set the value of correiosCode
     *
     * @param  string  $correiosCode
     *
     * @return  self
     */ 
    public function setCorreiosCode($correiosCode)
    {
        $this->correiosCode = $correiosCode;

        return $this;
    }

    /**
     * Get the value of correiosName
     *
     * @return  string
     */ 
    public function getCorreiosName()
    {
        return $this->correiosName;
    }

    /**
     * Set the value of correiosName
     *
     * @param  string  $correiosName
     *
     * @return  self
     */ 
    public function setCorreiosName($correiosName)
    {
        $this->correiosName = $correiosName;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of enabled
     *
     * @return  boolean
     */ 
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set the value of enabled
     *
     * @param  boolean  $enabled
     *
     * @return  self
     */ 
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }
}