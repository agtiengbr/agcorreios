<?php

namespace AGTI\Correios\Infrastructure\Mapping;

use AGTI\Cliente\Mapping\NumberMapping;

class AddressNumberMapping extends NumberMapping
{
    protected $configName = 'agCorreios_address_number';

    public function isMappingEnabled()
    {
        return $this->getMappedValue() != 'mapping_disabled' && $this->getMappedValue() != '';
    }    
}