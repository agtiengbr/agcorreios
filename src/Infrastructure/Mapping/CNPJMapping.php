<?php

namespace AGTI\Correios\Infrastructure\Mapping;

use AGTI\Cliente\Mapping\CNPJMapping as MappingCNPJMapping;

class CNPJMapping extends MappingCNPJMapping
{
    protected $configName = 'agCorreios_cnpj';

    public function isMappingEnabled()
    {
        return $this->getMappedValue() != 'mapping_disabled' && $this->getMappedValue() != '';
    }
}