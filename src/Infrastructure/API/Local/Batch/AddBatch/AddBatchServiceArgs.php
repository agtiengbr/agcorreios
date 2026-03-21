<?php

namespace AGTI\Correios\Infrastructure\API\Local\Batch\AddBatch;

class AddBatchServiceArgs
{
    private $tmpFileName;

    /**
     * Get the value of tmpFileName
     */ 
    public function getTmpFileName()
    {
        return $this->tmpFileName;
    }

    /**
     * Set the value of tmpFileName
     *
     * @return  self
     */ 
    public function setTmpFileName($tmpFileName)
    {
        $this->tmpFileName = $tmpFileName;

        return $this;
    }
}