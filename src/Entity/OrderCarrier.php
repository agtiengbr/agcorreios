<?php


namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class OrderCarrier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_order_carrier", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Orders", cascade={"persist"})
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id_order")
     */
    private $order;

    /**
     * @ORM\Column(name="shipping_cost_tax_incl", type="float")
     */
    private $shippingCost;

    /**
     * @ORM\Column(type="string")
     */
    private $trackingNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdd;
    
    /**
     * @var Carrier
     * 
     * @ORM\ManyToOne(targetEntity="Carrier")
     * @ORM\JoinColumn(name="id_carrier", referencedColumnName="id_carrier")
     */
    private $carrier;
    
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
     * Get the value of order
     */ 
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */ 
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of carrier
     */ 
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * Set the value of carrier
     *
     * @return  self
     */ 
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;

        return $this;
    }

    /**
     * Get the value of shippingCost
     */ 
    public function getShippingCost()
    {
        return $this->shippingCost;
    }

    /**
     * Set the value of shippingCost
     *
     * @return  self
     */ 
    public function setShippingCost($shippingCost)
    {
        $this->shippingCost = $shippingCost;

        return $this;
    }

    /**
     * Get the value of trackingNumber
     */ 
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * Set the value of trackingNumber
     *
     * @return  self
     */ 
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;

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
}