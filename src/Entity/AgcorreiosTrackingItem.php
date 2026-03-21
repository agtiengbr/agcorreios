<?php

namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class AgcorreiosTrackingItem
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column()
    */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var float
     * 
     * @ORM\Column(type="float")
     */
    private $qty;

    /**
     * @var float
     * 
     * @ORM\Column(type="float")
     */
    private $unitPrice;

    /**
     * @var float
     * 
     * @ORM\Column(type="float")
     */
    private $width;

    /**
     * @var float
     * 
     * @ORM\Column(type="float")
     */
    private $height;

    /**
     * @var float
     * 
     * @ORM\Column(type="float")
     */
    private $depth;

    /**
     * @var float
     * 
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    private $sku;

    /**
     * @var AgcorreiosTracking
     * @ORM\ManyToOne(targetEntity="AgcorreiosTracking")
     * @ORM\JoinColumn(name="id_tracking", referencedColumnName="id_agcorreios_tracking")
     */
    private $label;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $dateAdd;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $dateUpd;


    /**
     * Get the value of dateUpd
     *
     * @return  \DateTime
     */ 
    public function getDateUpd()
    {
        return $this->dateUpd;
    }

    /**
     * Set the value of dateUpd
     *
     * @param  \DateTime  $dateUpd
     *
     * @return  self
     */ 
    public function setDateUpd(\DateTime $dateUpd)
    {
        $this->dateUpd = $dateUpd;

        return $this;
    }

    /**
     * Get the value of dateAdd
     *
     * @return  \DateTime
     */ 
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set the value of dateAdd
     *
     * @param  \DateTime  $dateAdd
     *
     * @return  self
     */ 
    public function setDateAdd(\DateTime $dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get the value of label
     *
     * @return  AgcorreiosTracking
     */ 
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @param  AgcorreiosTracking  $label
     *
     * @return  self
     */ 
    public function setLabel(AgcorreiosTracking $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of sku
     *
     * @return  string
     */ 
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set the value of sku
     *
     * @param  string  $sku
     *
     * @return  self
     */ 
    public function setSku(string $sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get the value of weight
     *
     * @return  float
     */ 
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of weight
     *
     * @param  float  $weight
     *
     * @return  self
     */ 
    public function setWeight(float $weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get the value of depth
     *
     * @return  float
     */ 
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set the value of depth
     *
     * @param  float  $depth
     *
     * @return  self
     */ 
    public function setDepth(float $depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get the value of height
     *
     * @return  float
     */ 
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of height
     *
     * @param  float  $height
     *
     * @return  self
     */ 
    public function setHeight(float $height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the value of width
     *
     * @return  float
     */ 
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of width
     *
     * @param  float  $width
     *
     * @return  self
     */ 
    public function setWidth(float $width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the value of unitPrice
     *
     * @return  float
     */ 
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set the value of unitPrice
     *
     * @param  float  $unitPrice
     *
     * @return  self
     */ 
    public function setUnitPrice(float $unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get the value of qty
     *
     * @return  float
     */ 
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set the value of qty
     *
     * @param  float  $qty
     *
     * @return  self
     */ 
    public function setQty(float $qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

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
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setDateAdd(new \DateTime);        
        $this->setDateUpd(new \DateTime);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setDateUpd(new \DateTime);
    }
}