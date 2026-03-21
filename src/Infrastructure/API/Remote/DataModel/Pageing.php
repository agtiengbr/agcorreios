<?php
namespace AGTI\Correios\Infrastructure\API\Remote\DataModel;

class Pageing
{
    /** @var integer  */
    private $size;

    /** @var integer */
    private $totalElements;

    /** @var integer  */
    private $totalPages;

    /** @var integer */
    private $number;

    /**
     * Get the value of size
     */ 
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @return  self
     */ 
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of totalElements
     */ 
    public function getTotalElements()
    {
        return $this->totalElements;
    }

    /**
     * Set the value of totalElements
     *
     * @return  self
     */ 
    public function setTotalElements($totalElements)
    {
        $this->totalElements = $totalElements;

        return $this;
    }

    /**
     * Get the value of totalPages
     */ 
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Set the value of totalPages
     *
     * @return  self
     */ 
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    /**
     * Get the value of number
     */ 
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the value of number
     *
     * @return  self
     */ 
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }
}