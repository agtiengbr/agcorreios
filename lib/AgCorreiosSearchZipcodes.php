<?php

class AgCorreiosSearchZipcodes
{
    /**
     * @return Array
     */
    public static function getCities()
    {
        $r = AgCommunicator::doCurlRequest("https://enderecos.agti.eng.br/postcodes/cities", null, null, false);
        return json_decode($r);
    }
}