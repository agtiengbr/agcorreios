<?php

namespace AGTI\Correios\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 */
class AgcorreiosConcilRows
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_agcorreios_concil_rows", type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $trackingNumber;

    /**
     * @ORM\ManyToOne(targetEntity="AgcorreiosConcilBatch")
     * @ORM\JoinColumn(name="id_agcorreios_concil_batch", referencedColumnName="id_agcorreios_concil_batch")
     */
    private $batch;

    /**
     * @ORM\OneToOne(targetEntity="OrderCarrier")
     * @ORM\JoinColumn(name="id_order_carrier", referencedColumnName="id_order_carrier")
     */
    private $orderCarrier;
    

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdd;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAnalysis;


    /**
     * Get the value of dateAnalysis
     */ 
    public function getDateAnalysis()
    {
        return $this->dateAnalysis;
    }

    /**
     * Set the value of dateAnalysis
     *
     * @return  self
     */ 
    public function setDateAnalysis($dateAnalysis)
    {
        $this->dateAnalysis = $dateAnalysis;

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
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of batch
     */ 
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * Set the value of batch
     *
     * @return  self
     */ 
    public function setBatch($batch)
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * Get the value of orderCarrier
     */ 
    public function getOrderCarrier()
    {
        return $this->orderCarrier;
    }

    /**
     * Set the value of orderCarrier
     *
     * @return  self
     */ 
    public function setOrderCarrier($orderCarrier)
    {
        $this->orderCarrier = $orderCarrier;

        return $this;
    }

    /**
     * Get the value of cost
     */ 
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set the value of cost
     *
     * @return  self
     */ 
    public function setCost($cost)
    {
        $this->cost = $cost;

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
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setDateAdd(new \DateTime);        
    }
}