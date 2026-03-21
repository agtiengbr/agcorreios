<?php

namespace AGTI\Correios\Infrastructure\Repository;

use AGTI\Correios\ValueObject\Configuration as VBConfiguration;
use Symfony\Component\Serializer\Serializer;

class Configuration
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function loadConfig()
    {
        $dt = \Configuration::get("AGCORREIOS_CONFIG");
        if ($dt) {
            $ret = $this->serializer->deserialize($dt, VBConfiguration::class, 'json');
            
            return $ret;
        } else {
            return new VBConfiguration;
        }
    }

    public function storeConfig(VBConfiguration $configuration)
    {
        \Configuration::updateValue("AGCORREIOS_CONFIG", $this->serializer->serialize($configuration, 'json'));
    }
}