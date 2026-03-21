<?php
use AgCorreios\Exceptions\OverWeightException;
use AgCorreios\Exceptions\UnreachableAddressException;
use AGTI\Correios\Application\Service\PrazoNacional;
use AGTI\Correios\Application\Service\PrecoNacional;
use AGTI\Correios\Application\Service\TokenRetriever;
use AGTI\Correios\ValueObject\Configuration as VBConfiguration;

class AgCorreiosPrices extends AgObjectModel
{
    public static $definition = array(
        'table'     => 'agcorreios_price',
        'primary'   => 'id_agcorreios_price',
        'multilang' => false,
        'fields'    => array(
            'id_agcorreios_price' => array('type' => self::TYPE_INT,'validate' => 'isInt'),
            'zipcode' => array('type' => self::TYPE_INT, 'validate' => 'isGenericName', 'db_type' => 'int unsigned'),
            'weight' => array('type' => self::TYPE_FLOAT, 'db_type' => 'float', 'required' => true),
            'shipping_cost' => array('type' => self::TYPE_FLOAT, 'db_type' => 'float', 'required' => true),
            'id_agcorreios_service' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'db_type' => 'int unsigned', 'required' => true),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'db_type' => 'int unsigned', 'required' => true),
            'delivery_time' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'db_type' => 'int unsigned',
                'required' => true),
            'id_agcorreios_interval' => ['type' => self::TYPE_INT, 'db_type' => 'int unsigned'],
            'city' => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'],
            'state' => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'],
            'district' => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(255)'],

            'recalculate' => ['type' => self::TYPE_BOOL, 'db_type' => 'boolean'],

            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_upd' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
        ),
        'indexes' => [
            [
                'fields' => ['id_shop', 'id_agcorreios_service', 'zipcode', 'weight'],
                'prefix' => 'unique',
                'name' => 'unique_zipcode_weight_per_service_and_shop'
            ],
            [
                'fields' => ['id_shop', 'recalculate'],
                'name' => 'recalculate_idx'
            ]
        ]
    );

    public $id_agcorreios_price;
    public $zipcode;
    public $weight;
    public $shipping_cost;
    public $id_agcorreios_service;
    public $id_shop;
    public $delivery_time;
    public $id_agcorreios_interval;
    public $city;
    public $state;
    public $district;
    public $recalculate;
    public $date_add;
    public $date_upd;

    public static function get($zipcode, $weight, $id_agcorreios_service, $id_shop)
    {
        $service = new AgCorreiosServices($id_agcorreios_service);
        $weights = explode(';', $service->weights);

        //busca a faixa de peso do peso cujo valor do frete está sendo calculado
        $range_weight = [];
        for ($i=0; $i<count($weights)-1; $i++) {
            if ($weights[$i] <= $weight && $weights[$i+1] > $weight) {
                $range_weight[0] = $weights[$i];
                $range_weight[1] = $weights[$i+1];
                break;
            }
        }

        if (count($range_weight) === 0) {
            return false;
        }

        $sql = new DbQuery;
        $sql->from('agcorreios_price')
            ->where('id_agcorreios_service ='. (int)$id_agcorreios_service)
            ->where('weight >= ' . (float) $range_weight[0])
            ->where('weight <' . (float)$range_weight[1])
            ->where('id_shop =' . (int) $id_shop);

        if ($service->interval_mode == 0) {
            //busca a faixa de CEP
            $interval = AgCorreiosInterval::getFromZipCode($zipcode);
            if (!$interval) {
                return;
            }
            
            $sql->where('zipcode >= ' . (int) $interval['zipcode_begin'])
                ->where('zipcode <= ' . (int) $interval['zipcode_end']);
        } else {
            $sql->where('zipcode = ' . (int)$zipcode);
        }
        
        $db_data = Db::getInstance()->getRow($sql);
        if (!$db_data) {
            return;
        }

        $return = new AgCorreiosPrices;
        $return->hydrate($db_data);

        return $return;
    }

    //calcula o frete usando o webservice dos correios
    public static function calcExternal(
        $correios_code,
        $postcode_from,
        $postcode_to,
        $weight,
        $height,
        $width,
        $depth,
        $product_value,
        $aviso_recebimento,
        $maos_proprias,
        $save_in_db=true,
        $redirect_to_correios=false,
        $contract_number='',
        $contract_password=''
    )
    {
        //calcula o peso cúbico (VER NGCOR-98)
        $width  = max($width, 10);
        $depth  = max($depth, 10);
        $height = max($height, 2);
        $weight = max($weight, 0.1);

        $volume = $width * $height * $depth;
        $cubic_weight = $volume / 6000;
        if ($cubic_weight > 5) {
            $weight_offline = max($weight, $cubic_weight);
        } else {
            $weight_offline = $weight;
        }

        
        $serviceOm = AgCorreiosServices::getByCorreios($correios_code);
        $range_weight = [];
        $weights = explode(';', $serviceOm->weights);

        for ($i=0; $i<count($weights)-1; $i++) {
            if ((float) $weights[$i] <= $weight_offline && (float) $weights[$i+1] > $weight_offline) {
                $range_weight[0] = $weights[0];
                $range_weight[1] = $weights[1];
                break;
            }
        }

        //se o serviço não possui esse peso em suas faixas, aborta
        if (count($range_weight) === 0) {
            return;
        }



        AgClienteLogger::addLog("calculo externo.", 1, null, null, null, true);

        $module = new agcorreios;
        $config = $module->get(VBConfiguration::class);
        if ($config->getUsername()) {
            try {
                $postcode_from = preg_replace("/[^0-9]/", "", $postcode_from);
                $postcode_to = preg_replace("/[^0-9]/", "", $postcode_to);


                $postcode_from = str_pad($postcode_from, 8, 0, STR_PAD_LEFT);
                $postcode_to = str_pad($postcode_to, 8, 0, STR_PAD_LEFT);

                $config = $module->get(VBConfiguration::class);

                $serviceToken = $module->get(TokenRetriever::class);
                $token = $serviceToken->exec(
                    $config->getUsername(),
                    $config->getPassword(),
                    $config->getCartaoPostagem()
                );

                $service = $module->get(PrazoNacional::class);
                $prazoResponse = $service->exec($correios_code, $postcode_from, $postcode_to, $token);

                $servicePreco = $module->get(PrecoNacional::class);
                $priceResponse = $servicePreco->exec($correios_code, $postcode_from, $postcode_to, $width, $depth, $height, 1000 * $weight, $token, $config->getContractNumber(), $config->getDrNumber());

                $price = [
                    'total' => $priceResponse->getPcfinal(),
                    'prazo' => $prazoResponse->getPrazoEntrega()
                ];

                goto after_calculation;
            } catch(Exception $e) {
                $price = [
                    'total' => 0,
                    'prazo' => 0
                ];
            }
        }

        after_calculation:
        if ($save_in_db) {
            $obj = self::get($postcode_to, $weight_offline, $serviceOm->id, Context::getContext()->shop->id);
            if (Validate::isLoadedObject($obj)) {
                $address = AddressFinder::findAgti($postcode_to);

                if (is_object($address)) {
                    $obj->city = $address->city;
                    $obj->state = $address->state;
                    $obj->district = $address->district;
                }

                $obj->shipping_cost = @$price['total'] ?: 0;
                $obj->delivery_time = @$price['prazo'] ?: 0;

                $obj->recalculate = 0;
                $obj->update();
                goto _return;
            }

            //busca a faixa de peso do peso cujo valor do frete está sendo calculado
           

            $interval = AgCorreiosInterval::getFromZipCode($postcode_to);
            
            if (!$interval) {
                $interval_obj = new AgCorreiosInterval;

                $interval_obj->zipcode_begin = sprintf("%08d", $postcode_to);
                $interval_obj->zipcode_end = sprintf("%08d", $postcode_to);

                $address = AddressFinder::findByPostcode($postcode_to);
                if (is_object($address)) {
                    $interval_obj->state = $address->state;
                    $interval_obj->city = $address->city;
                    $interval_obj->name = $address->state . ' - ' . $address->city;
                }

                $interval_obj->save(); 
                $interval = (array)$interval_obj;
            } else {
                $address = AddressFinder::findByPostcode($postcode_to);
            }

            $price_obj = new AgCorreiosPrices;
            $price_obj->id_agcorreios_service = $serviceOm->id;
            
            $price_obj->zipcode = $postcode_to;
            $price_obj->weight = $weight_offline;
            $price_obj->shipping_cost = @$price['total'] ?: 0;
            $price_obj->id_shop = Context::getContext()->shop->id;
            $price_obj->delivery_time = @$price['prazo'] ?: 0;
            
            if (is_object($address)) {
                $price_obj->city = $address->city;
                $price_obj->state = $address->state;
                $price_obj->district = $address->district;
            }
            
            $price_obj->recalculate = false;
            $price_obj->save();
        }

        _return:
        return array(
            'price' => @$price['total'],
            'delay' => @$price['prazo']
        );
    }

    public function calcAndUpdate()
    {
        $mod = new agcorreios;
        $options = $mod->getOptions();

        $service = new AgCorreiosServices($this->id_agcorreios_service);
        self::calcExternal(
            $service->correios_code,
            $options['agcorreios_zipcode_origin'],
            $this->zipcode,
            $this->weight,
            2,
            20,
            20,
            0,
            false,
            false,
            true,
            false,
            $options['agcorreios_contract_number'],
            $options['agcorreios_contract_password']
        );
    }

    public static function getAllToBeCalculated()
    {
        $sql = new DbQuery;
        $sql->from('agcorreios_price', 'p')
            ->innerJoin('agcorreios_services', 's', 's.id_agcorreios_services=p.id_agcorreios_service')
            ->where('p.recalculate=1')
            ->where('s.enabled=1')
            ->where('p.id_shop=' . (int) Context::getContext()->shop->id)
            ->limit(80000);

        return Db::getInstance()->executeS($sql);
    }

    public static function calcAll()
    {
        /** @var AgClienteWorker */
        global $agti_worker;

        $mod = new agcorreios;
        $options = $mod->getOptions();
        $prices = self::getAllToBeCalculated();

        $indexes = $agti_worker->getInitAndEndIndexes(count($prices));

        AgClienteLogger::addLog("Worker {$agti_worker->idx} processando de {$indexes['begin']} até {$indexes['end']} de um total de " . count($prices));

        try {
            $prices = array_slice($prices, $indexes['begin'], $indexes['end'] - $indexes['begin'], true);
            foreach ($prices as $i=>$price) {
                $obj = new AgCorreiosPrices($price['id_agcorreios_price']);
                if (!$obj->recalculate) {
                    continue;
                }

                $agti_worker->save();

                $zipcode = $price['zipcode'];
                $weight = $price['weight'];
                $service = new AgCorreiosServices($price['id_agcorreios_service']);

                try {
                    calc:
                    AgClienteLogger::addLog("Testando CEP {$zipcode} e peso {$weight} (índice {$i} - #$obj->id).", 1);
                    $result = self::calcExternal(
                        $service->correios_code,
                        $options['agcorreios_zipcode_origin'],
                        $zipcode,
                        $weight,
                        4,
                        20,
                        16,
                        0,
                        false,
                        false,
                        false,
                        $options['agcorreios_contract_number'],
                        $options['agcorreios_contract_password']
                    );
                    AgClienteLogger::addLog(json_encode($result));
                    $obj->shipping_cost = @$result['price'] ?: 0;
                    $obj->delivery_time = @$result['delay'] ?: 0;

                    //testa o proximo CEP do intervalo até terminar
                    if ($obj->shipping_cost == 0) {
                        $interval = new AgCorreiosInterval($obj->id_agcorreios_interval);
                        if ($zipcode < $interval->zipcode_end) {
                            $zipcode++;
                            goto calc;
                        }
                    }

                    $obj->recalculate = 0;
                    $obj->save();
                    
                } catch (OverWeightException $e) {
                    //se esse serviço nao entrega para esse peso, desmarca o recálculo para todos os preços desse mesmo peso e serviço
                    Db::getInstance()->update(
                        'agcorreios_price',
                        [
                            'recalculate' => 0,
                            'shipping_cost' => 0
                        ],
                        'id_shop=' . Context::getContext()->shop->id . ' AND weight=' . (float)$weight . ' AND id_agcorreios_service=' . (int)$service->id
                    );
                } catch (UnreachableAddressException $e) {
                    //se esse serviço nao entrega para esse CEP, desmarca o recálculo para todos os preços desse mesmo CEP e serviço
                    Db::getInstance()->update(
                        'agcorreios_price',
                        [
                            'recalculate' => 0,
                            'shipping_cost' => 0
                        ],
                        'id_shop=' . Context::getContext()->shop->id . ' AND zipcode=' . (int)$zipcode . ' AND id_agcorreios_service=' . (int)$service->id
                    );
                } catch (Exception $e) {
                    AgClienteLogger::addLog("Erro calculando preço {$obj->id} - " . $e->getMessage(), 3, null, null, null, true);
                    $obj->recalculate = 0;
                    $obj->save();
                }
            }
        } catch (Exception $e) {
            AgClienteLogger::addLog("Erro fatal - " . $e->getMessage(), 4);
        }
    }


    //ATUALIZAR na 2.0.0!
    public function generateCsvForSkyhub()
    {
        
        exit();
    }
 
    public static function createAll()
    {
        global $agti_worker;

        $services = AgCorreiosServices::getAll();

        $s = (int)Configuration::get("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_s");
        for ($s; $s< count($services); $s++){
            $agti_worker->save();
            Configuration::updateValue("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_s", $s);
            $service = $services[$s];
            $agti_worker->save();

            if (!$service->enabled) {
                continue;
            }

            AgClienteLogger::addLog("Verificando serviço {$service->id}.");

            $service_percent = $service->getPercentFilled();
            AgClienteLogger::addLog("{$service_percent}% preenchido.");

            if ($service_percent >= 100) {
                AgClienteLogger::addLog("Serviço já preenchido. Ignorando.");
                $service->intervals_created = 1;
                $service->update();

                continue;
            }

            AgClienteLogger::addLog("Iniciando checagem dos preços.");

            $weights = explode(';', $service->weights);
            $w = (int)Configuration::get("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_w");
            if (!$w) {
                $w = 1;
            }

            for ($w; $w<count($weights); $w++) {
                AgClienteLogger::addLog("w=$w");
                $agti_worker->save();
                Configuration::updateValue("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_w", $w);

                
                $weight_percent = $service->getPercentFilledByWeightRange($weights[$w], $weights[$w-1]);

                AgClienteLogger::addLog("Peso de {$weights[$w]} a {$weights[$w-1]} = {$weight_percent}%");

                if ($weight_percent == 100) {
                    AgClienteLogger::addLog("Peso de {$weights[$w]} a {$weights[$w-1]} já preenchido. Ignorando.");
                    continue;
                }

                $weight = ($weights[$w] + $weights[$w-1]) / 2;

                $intervals = AgCorreiosInterval::getAll();
                $indexes = $agti_worker->getInitAndEndIndexes(count($intervals));
                AgClienteLogger::addLog("Worker {$agti_worker->idx} processando de {$indexes['begin']} até {$indexes['end']} de um total de " . count($intervals));
                $intervals = array_slice($intervals, max((int) $indexes['begin']-1, 0), (int) ($indexes['end'] - $indexes['begin']+2));

                $i = (int)Configuration::get("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_i");
                for ($i; $i<count($intervals); $i++) {
                    $agti_worker->save();
                    Configuration::updateValue("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_i", $i);

                    $interval = $intervals[$i];
                    AgClienteLogger::addLog("i = $i / " . count($intervals) . json_encode($interval));

                    if (self::get($interval['zipcode_end'], $weight, $service->id, Context::getContext()->shop->id)) {
                        continue;
                    }

                    AgClienteLogger::addLog("Criando para o intervalo $i.");
                    $price = new AgCorreiosPrices;

                    $price->zipcode = sprintf("%08d", $interval['zipcode_end']);
                    $price->weight = ($weights[$w] + $weights[$w-1]) / 2;;
                    $price->shipping_cost = 0;
                    $price->id_agcorreios_service = $service->id;
                    $price->id_shop = Context::getContext()->shop->id;
                    $price->delivery_time = 0;
                    $price->id_agcorreios_interval = $interval['id_agcorreios_interval'];
                    $price->city = $interval['city'];
                    $price->state = $interval['state'];
                    $price->recalculate = 1;

                    $price->save();

                    usleep(1000);
                }
                Configuration::updateValue("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_i", 0);
            }

            Configuration::updateValue("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_w", 1);

            $service->intervals_created = 1;
            $service->update();
        }
        Configuration::updateValue("AGCORREIOS_PRICES_CREATEALL_W{$agti_worker->idx}_s", 0);

        return;

        $dbPrefix = _DB_PREFIX_;

        if (!Configuration::get('AGCORREIOS_PRICES_CREATE_ALL_RUNNING')) {
            AgClienteLogger::addLog("Iniciando novo preenchimento.");
            if ($id_agcorreios_service) {
                $services = [new AgCorreiosServices($id_agcorreios_service)];
                Db::getInstance()->delete('agcorreios_price', 'id_agcorreios_service=' . (int)$id_agcorreios_service);
            } else {
                Db::getInstance()->execute("TRUNCATE TABLE {$dbPrefix}agcorreios_price");
                $services = AgCorreiosServices::getAll();
            }
        } else {
            if ($id_agcorreios_service) {
                $services = [new AgCorreiosServices($id_agcorreios_service)];
            } else {
                $services = AgCorreiosServices::getAll();
            }
            AgClienteLogger::addLog("Continuando preenchimento antigo.");
        }

        Configuration::updateValue('AGCORREIOS_PRICES_CREATE_ALL_RUNNING', 1);

        foreach ($services as $service) {
            if (is_array($service)) {
                $service = new AgCorreiosServices($service['id_agcorreios_service']);
            }

            $service->intervals_created = 0;
            $service->update();
        }
        

        if (Configuration::hasKey('AGCORREIOS_PRICES_CREATE_ALL_S')) {
            $s = Configuration::get('AGCORREIOS_PRICES_CREATE_ALL_S');
        } else {
            $s = 0;
        }

        for ($s; $s<count($services); $s++) {
            AgClienteLogger::addLog("s = $s / " . count($services));

            Configuration::updateValue('AGCORREIOS_PRICES_CREATE_ALL_S', $s);
            $service = $services[$s];
            AgClienteLogger::addLog("Processando serviço {$service->id}.");


            if (is_array($service)) {
                $service = new AgCorreiosServices($service['id_agcorreios_service']);
            }

            if (!$service->enabled) {
                AgClienteLogger::addLog("Serviço inativo. Pulando.");
                continue;
            }

            $weights = explode(';', $service->weights);
            AgClienteLogger::addLog("Iniciando loops.");

            if (Configuration::hasKey('AGCORREIOS_PRICES_CREATE_ALL_W')) {
                $w = Configuration::get('AGCORREIOS_PRICES_CREATE_ALL_W');
            } else {
                $w = 1;
            }

            for ($w; $w<count($weights); $w++) {
                AgClienteLogger::addLog("w = $w / " . count($weights));
                Configuration::updateValue('AGCORREIOS_PRICES_CREATE_ALL_W', $w);

                $intervals = AgCorreiosInterval::getAll();
                
                if (Configuration::hasKey('AGCORREIOS_PRICES_CREATE_ALL_I')) {
                    $i = Configuration::get('AGCORREIOS_PRICES_CREATE_ALL_I');
                } else {
                    $i = 1;
                }

                for ($i; $i < count($intervals); $i++) {
                    AgClienteLogger::addLog("i = $i / " . count($intervals));
                    Configuration::updateValue('AGCORREIOS_PRICES_CREATE_ALL_I', $i);

                    $interval = $intervals[$i];

                    $price = new AgCorreiosPrices;

                    $price->zipcode = sprintf("%08d", $interval['zipcode_end']);
                    $price->weight = ($weights[$w] + $weights[$w-1]) / 2;;
                    $price->shipping_cost = 0;
                    $price->id_agcorreios_service = $service->id;
                    $price->id_shop = Context::getContext()->shop->id;
                    $price->delivery_time = 0;
                    $price->id_agcorreios_interval = $interval['id_agcorreios_interval'];
                    $price->city = $interval['city'];
                    $price->state = $interval['state'];
                    $price->recalculate = 1;

                    $price->save();

                    usleep(1000);
                }
                Configuration::updateValue('AGCORREIOS_PRICES_CREATE_ALL_I', 1);
            }

            Configuration::hasKey('AGCORREIOS_PRICES_CREATE_ALL_W', 0);
            AgClienteLogger::addLog("Finalizando serviço.");

            $service->intervals_created = 1;
            $service->update();
        }

        Configuration::updateValue('AGCORREIOS_PRICES_CREATE_ALL_S', 0);
        Configuration::updateValue('AGCORREIOS_PRICES_CREATE_ALL_RUNNING', 0);
        
        AgClienteLogger::addLog("Finalizando função.");
    }

    public static function setIdIntervalForAllPrices()
    {
        $sql = new DbQuery;
        $sql->from('agcorreios_price')
            ->where('id_agcorreios_interval=0')
            ->limit('100000');

        $prices = Db::getInstance()->executeS($sql);
        foreach ($prices as $price) {
            $interval = AgCorreiosInterval::getFromZipCode($price['zipcode']);
            
            $obj = new AgCorreiosPrices;
            $obj->hydrate($price);
            $obj->id = $obj->id_agcorreios_price;
            $obj->id_agcorreios_interval = $interval['id_agcorreios_interval'];
            $obj->save();
        }
    }
}
