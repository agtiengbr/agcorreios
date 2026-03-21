<?php

namespace AGTI\Correios\Infrastructure\Mapping;

use AGTI\Cliente\Mapping\IEMapping as MappingIEMapping;

class IEMapping extends MappingIEMapping
{
    protected $configName = 'agCorreios_ie';

    public function isMappingEnabled()
    {
        return $this->getMappedValue() != 'mapping_disabled' && $this->getMappedValue() != '';
    }    
}