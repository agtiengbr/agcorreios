<?php

namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_product", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $width;
    
    /**
     * @ORM\Column(type="float")
     */
    private $depth;
    
    /**
     * @ORM\Column(type="float")
     */
    private $height;
    

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
}