<?php

namespace AGTI\Correios\Infrastructure\Mapping;

use AGTI\Correios\Entity\Address;
use AGTI\Correios\Entity\Customer;
use AGTI\Correios\ValueObject\Mappings;

class MappingAdapter
{
    private $mappings;
    public function __construct(
        Mappings $mappings
    )
    {

        $this->mappings = $mappings;
    }

    public function getDocumentFromCustomer(Customer $customer)
    {
        if ($this->mappings->getCnpj()->isMappingEnabled() && $this->mappings->getCompanyName()->isMappingEnabled()) {
            if ($this->mappings->getCnpj()->getMappedValue() === 'djtalbrazilianregister') {
                $sql = new \DbQuery;
                $sql->from('djtalbrazilianregister')
                    ->where('id_customer=' . (int)$customer->getId());

                $data = \Db::getInstance()->getRow($sql);

                $cnpj    = @$data['cnpj'];
            } elseif ($this->mappings->getCnpj()->getMappedValue() === 'cpf_cnpj') {
                if (\Module::isEnabled('\psmodcpf')) {
                    include_once _PS_MODULE_DIR_ . '\psmodcpf/\psmodcpf.php';
                    if (!class_exists('\psmodcpf')) {
                        return;
                    }
    
                    $mod = new \psmodcpf;
                    $version = $mod->version;
    
                    if (version_compare($version, '2.0.6', '>=')) {
                        $column = 'tipo';
                    } else {
                        $column = 'tp_documento';
                    }
                
                    $sql = new \DbQuery;
                    $sql->from('modulo_cpf')
                        ->select('documento')
                        ->where('id_customer=' . (int)$customer->getId())
                        ->where("{$column}=2");
                    $cnpj = \Db::getInstance()->getValue($sql);
                } else {
                    //fkcustomers. Remover futuramente.
                    $sql = new \DbQuery;
                    $sql->from('customer')
                        ->where('id_customer=' . (int)$customer->getId());

                    $data = \Db::getInstance()->getRow($sql);
                    if ($data['tipo'] === 'pj') {
                        $cnpj = @$data['cpf_cnpj'];
                    }
                }
            } elseif ($this->mappings->getCnpj()->getMappedValue() === 'ldbrazilianregister') {
                $sql = new \DbQuery;
                $sql->from('ldbrazilianregister')
                    ->where('id_customer=' . (int)$customer->getId());

                $data = \Db::getInstance()->getRow($sql);
                $cnpj    = @$data['cnpj'];
            } elseif ($this->mappings->getCnpj()->getMappedValue() === '\psmodcpf') {
                $sql = new \DbQuery;
                $sql->from('modulo_cpf')
                    ->select('documento')
                    ->where('id_customer=' . (int)$customer->getId())
                    ->where('tp_documento="2"');
                $cnpj = \Db::getInstance()->getValue($sql);
            }else {
                $omCustomer = new \Customer($customer->getid());
                $cnpj = @$omCustomer->{$this->mappings->getCnpj()->getMappedValue()};
            }

            $omCustomer = new \Customer($customer->getid());
            $company = @$omCustomer->{$this->mappings->getCompanyName()->getMappedValue()};
        }

        if ($this->mappings->getCpf()->getMappedValue() === 'djtalbrazilianregister') {
            $sql = new \DbQuery;
            $sql->from('djtalbrazilianregister')
                ->where('id_customer=' . (int)$customer->getId());

            $data = \Db::getInstance()->getRow($sql);
            
            $cpf = @$data['cpf'];
            $name = $customer->getFirstname() . ' ' . $customer->getLastname();
        } elseif ($this->mappings->getCpf()->getMappedValue() === 'cpf_cnpj') {
            $sql = new \DbQuery;
            $sql->from('customer')
                ->where('id_customer=' . (int)$customer->getId());

            $data = \Db::getInstance()->getRow($sql);
            if ($data['tipo'] === 'pf') {
                $cpf = @$data['cpf_cnpj'];
            } else {
                $cnpj = @$data['cpf_cnpj'];
            }
        } elseif ($this->mappings->getCpf()->getMappedValue() === 'ldbrazilianregister') {
            $sql = new \DbQuery;
            $sql->from('ldbrazilianregister')
                ->where('id_customer=' . (int)$customer->getId());

            $data = \Db::getInstance()->getRow($sql);
            
            $cpf = @$data['cpf'];
            $name = $customer->getFirstname() . ' ' . $customer->getLastname();
        } elseif ($this->mappings->getCpf()->getMappedValue() === '\psmodcpf') {
            if (\Module::isEnabled('\psmodcpf')) {
                include_once _PS_MODULE_DIR_ . '\psmodcpf/\psmodcpf.php';
                if (!class_exists('\psmodcpf')) {
                    return;
                }

                $mod = new \psmodcpf;
                $version = $mod->version;

                if (version_compare($version, '2.0.6', '>=')) {
                    $column = 'tipo';
                } else {
                    $column = 'tp_documento';
                }
            
                $sql = new \DbQuery;
                $sql->from('modulo_cpf')
                    ->select('documento')
                    ->where('id_customer=' . (int)$customer->getId())
                    ->where("{$column}=1");
                $cpf = \Db::getInstance()->getValue($sql);
            }
        } else {
            $omCustomer = new \Customer($customer->getid());
            $cpf = @$omCustomer->{$this->mappings->getCpf()->getMappedValue()};
        }

        $name = $customer->getFirstname() . ' ' . $customer->getLastname();


        return [
            'cpf'     => $cpf,
            'name'    => $name,
            'cnpj'    => @$cnpj,
            'company' => @$company
        ];
    }

    public function getNumberFromAddress(Address $address)
    {
        $address_number = 's/n';
        $omAddress = new \Address($address->getId());
        if ($this->mappings->getAddressNumber()->isMappingEnabled()) {
            $address_number = @$omAddress->{$this->mappings->getAddressNumber()->getMappedValue()};
        }

        return $address_number;
    } 
}