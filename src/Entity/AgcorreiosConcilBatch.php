<?php

namespace AGTI\Correios\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 */
class AgcorreiosConcilBatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_agcorreios_concil_batch", type="integer")
     */
    private $id;
    
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
    private $dateAnalysisBegin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAnalysisEnd;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalRows;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $rowsNotFound;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $rowsPriceError;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $rowsOk;
    

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
     * Get the value of dateAnalysisBegin
     */ 
    public function getDateAnalysisBegin()
    {
        return $this->dateAnalysisBegin;
    }

    /**
     * Set the value of dateAnalysisBegin
     *
     * @return  self
     */ 
    public function setDateAnalysisBegin($dateAnalysisBegin)
    {
        $this->dateAnalysisBegin = $dateAnalysisBegin;

        return $this;
    }

    /**
     * Get the value of dateAnalysisEnd
     */ 
    public function getDateAnalysisEnd()
    {
        return $this->dateAnalysisEnd;
    }

    /**
     * Set the value of dateAnalysisEnd
     *
     * @return  self
     */ 
    public function setDateAnalysisEnd($dateAnalysisEnd)
    {
        $this->dateAnalysisEnd = $dateAnalysisEnd;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setDateAdd(new \DateTime);        
    }

    /**
     * Get the value of rowsOk
     */ 
    public function getRowsOk()
    {
        return $this->rowsOk;
    }

    /**
     * Set the value of rowsOk
     *
     * @return  self
     */ 
    public function setRowsOk($rowsOk)
    {
        $this->rowsOk = $rowsOk;

        return $this;
    }

    /**
     * Get the value of rowsPriceError
     */ 
    public function getRowsPriceError()
    {
        return $this->rowsPriceError;
    }

    /**
     * Set the value of rowsPriceError
     *
     * @return  self
     */ 
    public function setRowsPriceError($rowsPriceError)
    {
        $this->rowsPriceError = $rowsPriceError;

        return $this;
    }

    /**
     * Get the value of rowsNotFound
     */ 
    public function getRowsNotFound()
    {
        return $this->rowsNotFound;
    }

    /**
     * Set the value of rowsNotFound
     *
     * @return  self
     */ 
    public function setRowsNotFound($rowsNotFound)
    {
        $this->rowsNotFound = $rowsNotFound;

        return $this;
    }

    /**
     * Get the value of totalRows
     */ 
    public function getTotalRows()
    {
        return $this->totalRows;
    }

    /**
     * Set the value of totalRows
     *
     * @return  self
     */ 
    public function setTotalRows($totalRows)
    {
        $this->totalRows = $totalRows;

        return $this;
    }
}