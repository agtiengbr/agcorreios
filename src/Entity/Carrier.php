<?php

namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_carrier")
 * @ORM\Entity()
 */
class Carrier
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_carrier", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string")
     */
    private $name;

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
}