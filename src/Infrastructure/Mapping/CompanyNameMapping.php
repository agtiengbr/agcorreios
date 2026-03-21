<?php

namespace AGTI\Correios\Infrastructure\Mapping;

use AGTI\Cliente\Mapping\CompanyMapping;

class CompanyNameMapping extends CompanyMapping
{
    protected $configName = 'agCorreios_company';

    public function isMappingEnabled()
    {
        return $this->getMappedValue() != 'mapping_disabled' && $this->getMappedValue() != '';
    }    
}