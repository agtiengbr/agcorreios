<?php

namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class AgcorreiosTracking
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_agcorreios_tracking")
    */
    private $id;

    /**
     * @var Orders
     * 
     * @ORM\OneToOne(targetEntity="Orders")
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id_order")
     */
    private $order;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $trackingCode;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $finished;

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
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    private $idRemote;

    /**
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    private $serviceCode;

    /**
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $solicitarColeta;

    /**
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $logisticaReversa;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $prazoPostagem;

    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    private $statusAtual;

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
     * Get the value of order
     *
     * @return  Orders
     */ 
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @param  Orders  $order
     *
     * @return  self
     */ 
    public function setOrder(Orders $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of trackingCode
     *
     * @return  string
     */ 
    public function getTrackingCode()
    {
        return $this->trackingCode;
    }

    /**
     * Set the value of trackingCode
     *
     * @param  string  $trackingCode
     *
     * @return  self
     */ 
    public function setTrackingCode($trackingCode)
    {
        $this->trackingCode = $trackingCode;

        return $this;
    }

    /**
     * Get the value of finished
     *
     * @return  bool
     */ 
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set the value of finished
     *
     * @param  bool  $finished
     *
     * @return  self
     */ 
    public function setFinished($finished)
    {
        $this->finished = $finished;

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
     * Get the value of idRemote
     *
     * @return  string
     */ 
    public function getIdRemote()
    {
        return $this->idRemote;
    }

    /**
     * Set the value of idRemote
     *
     * @param  string  $idRemote
     *
     * @return  self
     */ 
    public function setIdRemote(string $idRemote)
    {
        $this->idRemote = $idRemote;

        return $this;
    }

    /**
     * Get the value of serviceCode
     *
     * @return  string
     */ 
    public function getServiceCode()
    {
        return $this->serviceCode;
    }

    /**
     * Set the value of serviceCode
     *
     * @param  string  $serviceCode
     *
     * @return  self
     */ 
    public function setServiceCode(string $serviceCode)
    {
        $this->serviceCode = $serviceCode;

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

    /**
     * Get the value of logisticaReversa
     *
     * @return  boolean
     */ 
    public function getLogisticaReversa()
    {
        return $this->logisticaReversa;
    }

    /**
     * Set the value of logisticaReversa
     *
     * @param  boolean  $logisticaReversa
     *
     * @return  self
     */ 
    public function setLogisticaReversa($logisticaReversa)
    {
        $this->logisticaReversa = $logisticaReversa;

        return $this;
    }

    /**
     * Get the value of solicitarColeta
     *
     * @return  boolean
     */ 
    public function getSolicitarColeta()
    {
        return $this->solicitarColeta;
    }

    /**
     * Set the value of solicitarColeta
     *
     * @param  boolean  $solicitarColeta
     *
     * @return  self
     */ 
    public function setSolicitarColeta($solicitarColeta)
    {
        $this->solicitarColeta = $solicitarColeta;

        return $this;
    }

    /**
     * Get the value of prazoPostagem
     *
     * @return  \DateTime
     */ 
    public function getPrazoPostagem()
    {
        return $this->prazoPostagem;
    }

    /**
     * Set the value of prazoPostagem
     *
     * @param  \DateTime  $prazoPostagem
     *
     * @return  self
     */ 
    public function setPrazoPostagem(\DateTime $prazoPostagem)
    {
        $this->prazoPostagem = $prazoPostagem;

        return $this;
    }

    /**
     * Get the value of statusAtual
     *
     * @return  int
     */ 
    public function getStatusAtual()
    {
        return $this->statusAtual;
    }

    /**
     * Set the value of statusAtual
     *
     * @param  int  $statusAtual
     *
     * @return  self
     */ 
    public function setStatusAtual($statusAtual)
    {
        $this->statusAtual = $statusAtual;

        return $this;
    }
}