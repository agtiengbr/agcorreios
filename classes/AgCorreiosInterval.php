<?php
class AgCorreiosInterval extends AgObjectModel
{
    public static $definition = array(
        'table'     => 'agcorreios_interval',
        'primary'   => 'id_agcorreios_interval',
        'multilang' => false,
        'fields'    => array(
            'id_agcorreios_interval' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'zipcode_begin' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'db_type' => 'varchar(8)'),
            'zipcode_end' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'db_type' => 'varchar(8)'),
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'db_type' => 'varchar(255)'),
            'state' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'db_type' => 'varchar(255)'),
            'city' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'db_type' => 'varchar(255)'),
        ),
        'indexes' => [
            [
                'fields' => ['zipcode_begin', 'zipcode_end'],
                'prefix' => 'unique',
                'name' => 'unique_zipcode_interval'
            ],
            [
                'fields' => ['state', 'city'],
                'prefix' => '',
                'name' => 'city_search'
            ]
        ]
    );

    public $id_agcorreios_interval;
    public $zipcode_begin;
    public $zipcode_end;
    public $name;
    public $state;
    public $city;

    public static function getAll()
    {
        $cache_key = get_called_class() . __FUNCTION__;
        if (!Cache::isStored($cache_key)) {
            $sql = new DbQuery;
            $sql->from('agcorreios_interval', 'i');

            $db_data = Db::getInstance()->executeS($sql);
            Cache::store($cache_key, $db_data);
        }

        return Cache::retrieve($cache_key);
    }

    public static function getFromZipCode($zipcode)
    {
        $sql = new DbQuery;
        $sql->from('agcorreios_interval', 'i')
            ->where('CAST(zipcode_begin AS SIGNED INTEGER) <= ' . (int)$zipcode)
            ->where('CAST(zipcode_end AS SIGNED INTEGER) >= ' . (int)$zipcode);

        $db_data = Db::getInstance()->getRow($sql);
        return $db_data;
    }

    public static function getIntersection($begin, $end, $id_interval)
    {
        $sql = new DbQuery;
        $sql->from('agcorreios_interval', 'i')
            ->where('CAST(zipcode_end AS SIGNED INTEGER) >= '  .(int) $begin)
            ->where('CAST(zipcode_begin AS SIGNED INTEGER) <= '  .(int) $end);

        if ($id_interval) {
            $sql->where('id_agcorreios_interval != ' . (int) $id_interval);
        }

        $db_data = Db::getInstance()->getRow($sql);
        return $db_data;
    }

    public static function hasIntersectionWithOtherInterval($begin, $end, $id_interval)
    {
        $intersection = self::getIntersection($begin, $end, $id_interval);
        return isset($intersection['id_agcorreios_interval']);
    }

    public static function findByCity($city, $state)
    {
        $sql = new DbQuery;
        $sql->from('agcorreios_interval');
        $sql->where('city="' . pSQL($city) . '"');
        $sql->where('state="' . pSQL($state) . '"');

        $db_data = Db::getInstance()->getRow($sql);
        if (!$db_data) {
            $db_data = [];
        }

        $obj = new AgCorreiosInterval;
        $obj->hydrate($db_data);

        return $obj;
    }

    //adiciona a validação para verificar CEPS em mais de um intervalo
    public function validateFields($die = true, $error_return = false)
    {        
        if (!parent::validateFields($die, $error_return)) {
            return false;
        }

        if ((int) $this->zipcode_begin > (int) $this->zipcode_end) {
            throw new PrestaShopException('O primeiro CEP do intervalo não deve ser maior que o último CEP do intervalo.');
        }

        $has_intersection = self::hasIntersectionWithOtherInterval(
            $this->zipcode_begin,
            $this->zipcode_end,
            $this->id
        );
        
        if ($has_intersection) {
            if ($die) {
                $intersection = self::getIntersection(
                    $this->zipcode_begin,
                    $this->zipcode_end,
                    $this->id
                );

                throw new PrestaShopException(
                    sprintf(
                        'Erro no intervalo %s - Faixa de CEP de %d a %d já está em uso na faixa de CEP %s.',
                        $this->name,
                        $this->zipcode_begin,
                        $this->zipcode_end,
                        $intersection['name']
                    )
                );
            }

            return false;
        }

        return true;
    }

    public static function createDefaultIntervals($force=false)
    {
        Db::getInstance()->execute("TRUNCATE TABLE " . _DB_PREFIX_ . "agcorreios_interval_tmp");

        $intervals = AgCorreiosSearchZipcodes::getCities();
        foreach ($intervals as $interval) {
            $obj = new AgCorreiosIntervalTmp();

            $obj->name = $interval->city . ' - ' . $interval->state;
            $obj->zipcode_begin = sprintf("%08d", $interval->zipcode_begin);
            $obj->zipcode_end = sprintf("%08d", $interval->zipcode_end);
            $obj->city = $interval->city;
            $obj->state = $interval->state;

            $obj->save();
        }
       
        $dbPrefix = _DB_PREFIX_;
        Db::getInstance()->execute("RENAME TABLE {$dbPrefix}agcorreios_interval to {$dbPrefix}agcorreios_delete, {$dbPrefix}agcorreios_interval_tmp to {$dbPrefix}agcorreios_interval");
        Db::getInstance()->execute("DROP TABLE {$dbPrefix}agcorreios_delete");

        $obj->createDatabase();
        Configuration::updateValue('AGCORREIOS_INTERVALS_CREATED', 1);
    }

    public function add($auto_date = true, $null_values = false)
    {
        $return = parent::add($auto_date, $null_values);
        Cache::clean(get_called_class() . '*');
        return $return;
    }

    public function update($auto_date = true, $null_values = false)
    {
        $return = parent::update($auto_date, $null_values);
        Cache::clean(get_called_class() . '*');
        return $return;
    }    

    public function delete()
    {
        $return = parent::delete($auto_date, $null_values);
        Cache::clean(get_called_class() . '*');
        return $return;
    }


    public static function importCsvIntervals($filename){
        Logger::addLog('agcorreios - Iniciado importação de regiões ', '1', null, null, null, true);
        if($_FILES["fileUpload"]["size"] > 0){
            $file = fopen($filename, "r");

            $count =0;
            try {
                Db::getInstance()->execute("START TRANSACTION");

                $dbPrefix = _DB_PREFIX_;
                Db::getInstance()->execute("DELETE FROM {$dbPrefix}agcorreios_interval");
                Db::getInstance()->execute("DELETE FROM {$dbPrefix}agcorreios_price");

                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
                    usleep(10000);
                    $count++;

                        if($count != 1){
                            $interval= new AgCorreiosInterval();
                            $interval->zipcode_begin = $getData[0];
                            $interval->zipcode_end = $getData[1];
                            $interval->name = $getData[2];
                            $interval->state = $getData[3];
                            $interval->city = $getData[4];
                            
                            if(empty($interval->zipcode_begin) || !isset($interval->zipcode_begin) || empty($interval->zipcode_end) || !isset($interval->zipcode_end)){
                                throw new PrestaShopException('CEP de início e fim são obrigatorios.');
                            }

                            $interval->save();
                            $interval->validateFields();

                        }
                }
                Logger::addLog('agcorreios - Fim da importação de regiões.', '1', null, null, null, true);
            } catch (PrestaShopException $e) {
                Db::getInstance()->execute('Rollback');
                Logger::addLog('agcorreios - Erro da importação de regiões :'.$e->getMessage(), '2');
                fclose($file);  
                return $e;
            }

            if($count > 1){
                Db::getInstance()->execute('Commit');
                return true;

            }else{
                Db::getInstance()->execute('Rollback');
                return false;

            }
            fclose($file);  
        }
    }
}
