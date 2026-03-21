<?php

class AdminAgCorreiosConcilRowsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap    = true;
        $this->table        = 'agcorreios_concil_rows';
        $this->identifier   = 'id_agcorreios_concil_rows';
        $this->className    = 'AgCorreiosConcilRows';
        $this->noLink       = true;
        $this->list_no_link = true;

        parent::__construct();

        $dbPrefix = _DB_PREFIX_;
        $this->_join .= " LEFT JOIN {$dbPrefix}order_carrier oc ON oc.id_order_carrier = a.id_order_carrier ";
        $this->_join .= " LEFT JOIN {$dbPrefix}orders o ON o.id_order = oc.id_order";

        $this->_select .= 'o.reference as order_reference';

        $this->fields_list = [
            'id_agcorreios_concil_rows' => [
                'title' => 'ID'
            ],
            'tracking_number' => [
                'title' => 'Código de Rastreio'
            ],
            'cost' => [
                'title' => 'Valor Correios'
            ],
            'status' => [
                'title' => 'Estado',
                'type' => 'select',
                'list' => [
                    '0' => 'Processamento não Iniciado',
                    '1' => 'Conciliação Realizada'
                ],
                'filter_key' => 'a!status'
            ],
            'order_reference' => [
                'title' => 'Pedido'
            ],
            'date_analysis' => [
                'title' => 'Data Conciliação',
                'type' => 'datetime'
            ],
        ];
    }

    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        foreach ($this->_list as $i=>$row) {
            switch ($row['status']) {
                case '0':
                    $this->_list[$i]['status'] = 'Processamento não iniciado';
                    break;
                case '1':
                    $this->_list[$i]['status'] = 'Conciliação Realizada';
                    break;
            }
            
        }
    }
}