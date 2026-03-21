<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional;

class PrazoNacionalResponseError
{
    private $msgs;

    /**
     * Get the value of msgs
     */ 
    public function getMsgs()
    {
        return $this->msgs;
    }

    /**
     * Set the value of msgs
     *
     * @return  self
     */ 
    public function setMsgs($msgs)
    {
        $this->msgs = $msgs;

        return $this;
    }
}