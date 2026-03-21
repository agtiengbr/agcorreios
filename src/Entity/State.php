<?php

namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class State
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_state", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $isoCode;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;    
    

    /**
     * Get the value of isoCode
     */ 
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * Set the value of isoCode
     *
     * @return  self
     */ 
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

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
}