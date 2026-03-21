<?php

class AgCorreiosServices extends AgObjectModel
{
    public static $definition = array(
        'table'     => 'agcorreios_services',
        'primary'   => 'id_agcorreios_services',
        'multilang' => false,
        'fields'    => array(
            'id_agcorreios_services' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'correios_name' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),
            'delay' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),
            'carrier_name' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),

            'correios_code' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'),
            'carrier_id' => array('type' => self::TYPE_INT, 'db_type' => 'int unsigned'),

            'aviso_recebimento' => array('type' => self::TYPE_BOOL, 'db_type' => 'bool'),
            'mao_propria' => array('type' => self::TYPE_BOOL, 'db_type' => 'bool'),
            'valor_declarado' => array('type' => self::TYPE_BOOL, 'db_type' => 'bool'),

            
            'max_width' => ['type' => self::TYPE_FLOAT, 'db_type' => 'float'],
            'max_height' => ['type' => self::TYPE_FLOAT, 'db_type' => 'float'],
            'max_depth' => ['type' => self::TYPE_FLOAT, 'db_type' => 'float'],
            'max_sum_dimensions' => ['type' => self::TYPE_FLOAT, 'db_type' => 'float'],
            'enabled' => array('type' => self::TYPE_BOOL, 'db_type' => 'bool'),
            'handling_time' => array('type' => self::TYPE_INT, 'db_type' => 'int', 'validate' => 'isUnsignedInt'),

            'weights' => array('type' => self::TYPE_STRING, 'db_type' => 'varchar(1000)'),

            'interval_mode' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'shipping_method' => ['type' => self::TYPE_INT, 'db_type' => 'int'],

            'intervals_created' => array('type' => self::TYPE_BOOL, 'db_type' => 'bool'),
        ),
        'indexes' => array(
            array(
                'fields' => array('correios_code'),
                'prefix' => 'unique',
                'name' => 'unique_correios_code'
            ),
        )
    );

    public $id_agcorreios_services;
    public $correios_code;
    public $correios_name;

    public $carrier_name;
    public $delay;
    
    public $carrier_id;
    public $aviso_recebimento;
    public $mao_propria;
    public $valor_declarado;
    public $enabled;
    public $handling_time;

    public $max_width;
    public $max_height;
    public $max_depth;
    public $max_sum_dimensions;

    public $weights;
    public $intervals_created;

    //0: por faixa de CEP
    //1: por CEP único
    public $interval_mode;

    //0: tabela local
    //1: webservice
    public $shipping_method;

    protected static $services = array(
        array(
            'name' => 'PAC Varejo',
            'code' => '04510',
            'delay' => '5 a 15 dias úteis',
            'img_path' => 'pac.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),
        array(
            'name' => 'PAC Contrato Tarifa Atualizada',
            'code' => '04596',
            'delay' => '5 a 15 dias úteis',
            'img_path' => 'pac.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),
        array(
            'name' => 'SEDEX 10 Varejo',
            'code' => 40215,
            'delay' => '1 dia útil',
            'img_path' => 'sedex.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),

        ),
        array(
            'name' => 'SEDEX Varejo',
            'code' => '04014',
            'delay' => '1 a 5 dias úteis',
            'img_path' => 'sedex.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),
        array(
            'name' => 'SEDEX 12',
            'code' => 40169,
            'delay' => '1 dia útil',
            'img_path' => 'sedex.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),
        array(
            'name' => 'SEDEX 10 Pacote',
            'code' => 40886,
            'delay' => '1 dia útil',
            'img_path' => 'sedex.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),
        array(
            'name' => 'SEDEX Pagamento na Entrega',
            'code' => 40630,
            'delay' => '1 a 5 dias úteis',
            'img_path' => 'sedex.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),
        array(
            'name' => 'SEDEX Contrato Tarifa Atualizada',
            'code' => '04553',
            'delay' => '1 a 5 dias úteis',
            'img_path' => 'sedex.png',
            'weights' => array(0, 0.3, 0.5, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),
        array(
            'name' => 'Correios Mini Envios Varejo',
            'code' => '04227',
            'delay' => '5 a 15 dias úteis',
            'img_path' => 'pac.png',
            'weights' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1]
        ),
        array(
            'name' => 'Correios Mini Envios CTR Tarifa Atualizada',
            'code' => '04391',
            'delay' => '5 a 15 dias úteis',
            'img_path' => 'pac.png',
            'weights' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1]
        )
    );

    public static $preactivated_services = array(
        '04510', //PAC varejo
        '04014', //SEDEX varejo a vista,
        '04227' //MINI Envios
    );

    public static $free_module_services = array(
        '04510', //PAC varejo
        '04014', //SEDEX varejo a vista,
        '04227' //MINI Envios
    );

    public static function getAll()
    {
        $collection = new PrestaShopCollection('AgCorreiosServices');
        return $collection->getResults();
    }

    public static function createDefaultData()
    {
        self::installServices();
    }

    public static function installServices()
    {
        foreach (self::$services as $service) {
            $instance = self::getByCorreios($service['code']);

            //se o serviço já está cadastrado no banco, ignora
            if (Validate::isLoadedObject($instance)) {
                $carrier = new Carrier($instance->carrier_id);

                //se o serviço atual está mapeado a uma transportadora que está cadastrada na loja PS
                if ($carrier->id) {
                    //verifica se essa transportadora foi substituídao por alguma outra. em caso afirmativo, atualiza o ID
                    //da transportadora atuall
                    if ($carrier->id != $instance->carrier_id) {
                        $instance->carrier_id = $carrier->id;
                        $instance->update();
                    }
                }
                continue;
            }

            $instance = new AgCorreiosServices();
            $instance->carrier_name = $service['name'];
            $instance->correios_name = $service['name'];
            $instance->correios_code = $service['code'];
            $instance->carrier_id = 0;
            $instance->handling_time = 0;
            $instance->aviso_recebimento = 0;
            $instance->mao_propria = 0;
            $instance->valor_declarado = 0;

            if ($instance->correios_code == '40169' || $instance->correios_code == '40215') {
                $instance->interval_mode = 1;
            }

            $instance->max_width = 105;
            $instance->max_height = 105;
            $instance->max_depth = 105;
            $instance->max_sum_dimensions = 200;
            
            $instance->enabled = in_array($instance->correios_code, self::$preactivated_services);

            $instance->weights = implode(';', $service['weights']);
            if (!$instance->save()) {
                throw new Exception(Db::getInstance()->getMsgError());
            }
        }
    }

    public static function removeRangesForCarrier(Carrier $carrier)
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'range_price WHERE id_carrier=' . (int)$carrier->id;
        Db::getInstance()->execute($sql);

        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'range_weight WHERE id_carrier=' . (int)$carrier->id;
        Db::getInstance()->execute($sql);

        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'delivery WHERE id_carrier=' . (int)$carrier->id;
        Db::getInstance()->execute($sql);

        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'carrier_zone WHERE id_carrier=' . (int)$carrier->id;
        Db::getInstance()->execute($sql);
    }

    public function installCarrier()
    {
        $module = new AgCorreios();

        $carrier = [
            'name' => $this->carrier_name,
            'code' => $this->correios_code,
            'delay' => '5 a 15 dias úteis',
            'img_path' => 'pac.png',
        ];

        $carrier['id_tax_rules_group'] = 0;
        $carrier['active'] = $this->enabled;
        $carrier['deleted'] = 0;
        $carrier['shipping_handling'] = true;
        $carrier['range_behavior'] = 0;
        $carrier['_delay'] = $carrier['delay'];
        
        $carrier['id_zone'] = 1;
        $carrier['is_module'] = true;
        $carrier['shipping_external'] = true;
        $carrier['external_module_name'] = 'agcorreios';
        $carrier['need_range'] = true;

        $carrier['url'] = Context::getContext()->shop->getBaseURL() . 'modules/agcorreios/controllers/front/rastreamento.php?objeto=@';
        
        $id = self::installExternalCarrier($carrier);

        $this->carrier_id = $id;
        $this->update();
    }

    public static function installRangesForCarrier(Carrier $carrier)
    {
        self::removeRangesForCarrier($carrier);

        $rangePrice             = new RangePrice();
        $rangePrice->id_carrier = $carrier->id;
        $rangePrice->delimiter1 = '0';
        $rangePrice->delimiter2 = '1000000';
        $rangePrice->add();

        $zones = Zone::getZones(true);
        foreach ($zones as $zone) {
            Db::getInstance()->insert(
                'carrier_zone',
                array('id_carrier' => (int) ($carrier->id), 'id_zone' => (int) ($zone['id_zone'])
                )
            );
        }

        $rangePrice             = new RangeWeight();
        $rangePrice->id_carrier = $carrier->id;
        $rangePrice->delimiter1 = '0';
        $rangePrice->delimiter2 = '1000000';
        $rangePrice->add();
    }

    public static function installExternalCarrier($config)
    {
        $carrier                       = new Carrier();
        $carrier->name                 = $config['name'];
        $carrier->id_tax_rules_group   = $config['id_tax_rules_group'];
        $carrier->id_zone              = $config['id_zone'];
        $carrier->active               = $config['active'];
        $carrier->deleted              = $config['deleted'];
        $carrier->shipping_handling    = $config['shipping_handling'];
        $carrier->range_behavior       = $config['range_behavior'];
        $carrier->is_module            = $config['is_module'];
        $carrier->shipping_external    = $config['shipping_external'];
        $carrier->external_module_name = $config['external_module_name'];
        $carrier->need_range           = $config['need_range'];
        $carrier->url = $config['url'];

        $languages = Language::getLanguages(true);
        foreach ($languages as $language) {
            $carrier->delay[(int) $language['id_lang']] = $config['_delay'];
        }

        if ($carrier->add()) {            
            $groups = Group::getGroups(true);
            foreach ($groups as $group) {
                Db::getInstance()->insert(
                    'carrier_group',
                    array(
                        'id_carrier' => (int) ($carrier->id),
                        'id_group'   => (int) ($group['id_group']
                        ),
                    )
                );
            }

            self::installRangesForCarrier($carrier);
                        
            copy(_PS_MODULE_DIR_ . 'agcorreios/views/img/' . $config["img_path"], _PS_SHIP_IMG_DIR_ . (int) $carrier->id . '.png');

            // Return ID Carrier
            return (int) ($carrier->id);
        } else {
            throw new Exception(Db::getInstance()->getMsgError());
        }

        return false;
    }

    public static function uninstallServices()
    {
        foreach (self::getAll() as $service) {
            $service->delete();
        }
    }

    public static function getServices()
    {
        return self::$services;
    }

    public static function getByCorreios($service_code)
    {
        $query = new DbQuery();
        $query = $query->from('agcorreios_services');
        $query = $query->where('correios_code="' . pSQL($service_code) . '"');

        $service = Db::getInstance()->getRow($query);

        $return = new AgCorreiosServices();

        if (is_array($service)) {
            $return->hydrate($service);
        }
    
        return $return;
    }

    public static function getByCarrier($carrier_id)
    {
        $cache_key = get_called_class() . __FUNCTION__ . $carrier_id;

        if (!Cache::isStored($cache_key)) {
            $carrier = new Carrier($carrier_id);

            $collection = new PrestaShopCollection('AgCorreiosServices');
            $collection->where('carrier_id', '=', $carrier->id_reference);

            $obj = $collection->getFirst();

            Cache::store($cache_key, $obj);
        }
        
        return Cache::retrieve($cache_key);
    }

    public static function getCarrierIdFromServiceCode($service_code)
    {
        $service = self::getByCorreios($service_code);
        return $service->carrier_id;
    }

    public static function getServiceCodeFromCarrierId($carrier_id)
    {
        $service = self::getByCarrier($carrier_id);
        return $service->correios_code;
    }

    public static function mapServiceToCarrier($service_code, $carrier_id)
    {
        $instance = self::getByCorreios($service_code);
        $instance->carrier_id = $carrier_id;
        $instance->save();
    }

    public static function getNameOfService($service_code)
    {
        $services = AgCorreiosServices::getServices();

        foreach ($services as $service) {
            if ($service_code == $service['code']) {
                return $service['name'];
            }
        }
    }

    public function getCarrier()
    {
        return Carrier::getCarrierByReference($this->carrier_id);
    }

    public function add($auto_date = true, $null_values = false)
    {
        $return = parent::add($auto_date, $null_values);
        if ($return) {
            $this->id_agcorreios_services = $this->id;
            $this->installCarrier();
        }

        return $return;
    }

    public function update($null_values = false)
    {
        if (!parent::update($null_values)) {
            return false;
        }

        $carrier = $this->getCarrier();
        if (!Validate::isLoadedObject($carrier)) {
            return true;
        }

        $carrier->name = $this->carrier_name;
        $carrier->active = $this->enabled;

        $carrier->update();

        return true;
    }

    public function delete()
    {
        if (!parent::delete()) {
            return false;
        }

        $carrier = $this->getCarrier();

        if (Validate::isLoadedObject($carrier)) {
            $carrier->deleted = 1;
            $carrier->update();
        }

        return true;
    }

    public function getPercentFilledByWeightRange($weight1, $weight2)
    {
        $weight = ($weight2  + $weight1) / 2;
        $qty = Db::getInstance()->getValue(
            (new DbQuery)
                ->from('agcorreios_price')
                ->select('count(*)')
                ->where('id_agcorreios_service=' . (int)$this->id)
                ->where('ABS(weight-' . $weight . ') < 1E-3')
        );

        $intervals = AgCorreiosInterval::getAll();
        $expectedQty = count($intervals);

        AgClienteLogger::addLog("Total: {$qty}, esperado: {$expectedQty}");
        return Tools::ps_round(($qty / $expectedQty) * 100, 2);
    }

    public function getPercentFilled()
    {
        $weights = explode(';', $this->weights);
        $intervals = AgCorreiosInterval::getAll();

        $expectedQty = (count($weights) - 1) * count($intervals);


        $qty = Db::getInstance()->getValue(
            (new DbQuery)
                ->from('agcorreios_price')
                ->select('count(*)')
                ->where('id_agcorreios_service=' . (int)$this->id)
        );

        AgClienteLogger::addLog("Total: {$qty}, esperado: {$expectedQty}");
        if (!$expectedQty) {
            return 0;
        }
        return Tools::ps_round(($qty / $expectedQty) * 100, 2);
    }

    public function recalculatePrices()
    {
        Db::getInstance()->update(
            'agcorreios_price',
            [
                'recalculate' => 1
            ],
            'id_agcorreios_service=' . (int)$this->id
        );
    }
}
