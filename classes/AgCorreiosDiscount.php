<?php

class AgCorreiosDiscount extends AgObjectModel
{
    public static $definition = [
        'table'     => 'agcorreios_discount',
        'primary'   => 'id_agcorreios_discount',
        'multilang' => false,
        'fields'    => [
            'id_agcorreios_discount'   => ['type' => self::TYPE_INT,    'db_type' => 'int',         'validate' => 'isInt'],
            'id_agcorreios_service'    => ['type' => self::TYPE_INT,    'db_type' => 'int',         'validate' => 'isInt',         'required' => true],
            'alias'                    => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(50)', 'validate' => 'isGenericName', 'required' => true],
            'type_discount'            => ['type' => self::TYPE_INT,    'db_type' => 'int',   'validate' => 'isInt',               'required' => true],
            'discount'                 => ['type' => self::TYPE_FLOAT,  'db_type' => 'float', 'validate' => 'isFloat',             'required' => true],
            'postcode_begin'           => ['type' => self::TYPE_STRING,    'db_type' => 'varchar(11)'],
            'postcode_end'             => ['type' => self::TYPE_STRING,    'db_type' => 'varchar(11)'],
            'cart_value_begin'         => ['type' => self::TYPE_FLOAT,   'db_type' => 'float'],
            'cart_value_end'           => ['type' => self::TYPE_FLOAT,   'db_type' => 'float'],
            'active'                   => ['type' => self::TYPE_BOOL,   'db_type' => 'boolean', 'default' => 0],
            'id_shop'                  => ['type' => self::TYPE_INT,    'db_type' => 'int', 'validate' => 'isUnsignedId', 'default' => 0]
        ],
    ];

    public $id_agcorreios_discount;
    public $id_agcorreios_service;
    public $alias;
    public $type_discount;
    public $discount;
    public $cart_value_begin;
    public $cart_value_end;
    public $postcode_begin;
    public $postcode_end;
    public $active;
    public $id_shop;


    public static function hasIntersectionWithOtherInterval($zipcode_begin, $zipcode_end, $cart_value_begin, $cart_value_end, $id_agcorreios_service, $id_interval, $id_shop)
    {
        $sql = 'SELECT * FROM '.  _DB_PREFIX_ . 'agcorreios_discount ';
        $sql .= ' WHERE CAST(postcode_end AS SIGNED INTEGER) >= '  .(int) $zipcode_begin;
        $sql .= ' AND CAST(postcode_begin AS SIGNED INTEGER) <= '  .(int) $zipcode_end;
        $sql .= ' AND (cart_value_end  >= '  .(float) $cart_value_begin . ' OR cart_value_end = 0 OR cart_value_end IS NULL)';
        $sql .= ' AND cart_value_begin  <= '  .(float) $cart_value_end;
        $sql .= ' AND id_agcorreios_service=' . (int)$id_agcorreios_service;
        $sql .= ' AND id_shop=' . (int)$id_shop;

        if ($id_interval) {
            $sql .= ' AND id_agcorreios_discount != ' . (int) $id_interval;
        }

        $db_data = Db::getInstance()->getRow($sql);
        
        if (!is_array($db_data)) {
            $db_data = array();
        }

        $return = new AgCorreiosDiscount();
        $return->hydrate($db_data);

        return $return;
    }


    //adiciona a validação para verificar CEPS em mais de um intervalo ou região
    public function validateFields($die = true, $error_return = false)
    {        
        if (!parent::validateFields($die, $error_return)) {
            return false;
        }
        
        $intersection = self::hasIntersectionWithOtherInterval(
            $this->postcode_begin,
            $this->postcode_end,
            $this->cart_value_begin,
            $this->cart_value_end,
            $this->id_agcorreios_service,
            $this->id,
            $this->id_shop
        );
        
        if (Validate::isLoadedObject($intersection)) {
            if ($die) {
                throw new PrestaShopException(sprintf(
                    'O intervalo escolhido conflita com o desconto #%d (%s).',
                    $intersection->id,
                    $intersection->alias
                ));
            }

            return false;
        }

        return true;
    }

    public static function getDiscountByPostcodeAndPrice($postcode, $price, $id_agcorreios_service, $id_shop)
    {
        $postcode = str_replace('.', '', $postcode);
        $postcode = str_replace('-', '', $postcode);

        $sql = new DbQuery();
        $sql->from('agcorreios_discount')
            ->where('CAST(postcode_begin AS SIGNED INTEGER) <= ' . (int) $postcode)
            ->where('CAST(postcode_end AS SIGNED INTEGER) >= ' . (int) $postcode)
            ->where('cart_value_begin <= ' . (float) $price)
            ->where('cart_value_end >= ' . (float) $price . ' OR cart_value_end = 0 OR cart_value_end IS NULL')
            ->where('id_agcorreios_service=' . (int) $id_agcorreios_service)
            ->where('id_shop=' . (int) $id_shop)
            ->where('active=1');

        $discount = Db::getInstance()->getRow($sql);
        if (!is_array($discount)) {
            $discount = [];
        }

        $return = new AgCorreiosDiscount;
        $return->hydrate($discount);

        return $return;
    }

    public function applyTo($price)
    {
        if ($this->type_discount == 1) {
            $return = max(0, $price - $this->discount);
        } else {
            $return = max(0, $price * (1 - $this->discount / 100));
        }

        return $return;
    }
}
