<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional;

class PrecoNacionalResponseError
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