<?php

use AGTI\Correios\Infrastructure\API\Local\Batch\AddBatch\AddBatchService;
use AGTI\Correios\Infrastructure\API\Local\Batch\AddBatch\AddBatchServiceArgs;

class AdminAgCorreiosConcilController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap    = true;
        $this->table        = 'agcorreios_concil_batch';
        $this->identifier   = 'id_agcorreios_concil_batch';
        $this->className    = 'AgCorreiosConcilBatch';
        $this->noLink       = true;
        $this->list_no_link = true;

        parent::__construct();

        $this->fields_list = [
            'id_agcorreios_concil_batch' => [
                'title' => 'ID'
            ],
            'status' => [
                'title' => 'Estado',
                'type' => 'select',
                'list' => [
                    '0' => 'Processamento não Iniciado',
                    '1' => 'Em Progresso',
                    '2' => 'Processamento concluído',
                    '3' => 'Analisando Arquivo Enviado'
                ],
                'filter_key' => 'a!status'
            ],
            'total_rows' => [
                'type' => 'int',
                'title' => 'Total de Etiquetas'
            ],
            'rows_not_found' => [
                'type' => 'int',
                'title' => 'Etiquetas não encontradas'
            ],
            'rows_price_error' => [
                'type' => 'int',
                'title' => 'Divergência de Preço'
            ],
            'rows_ok' => [
                'type' => 'int',
                'title' => 'Conciliação OK'
            ],
            'date_add' => [
                'title' => 'Data de Upload do Arquivo',
                'type' => 'datetime'
            ],
            'date_analysis_begin' => [
                'title' => 'Data de Início da Análise',
                'type' => 'datetime'
            ],
            'date_analysis_end' => [
                'title' => 'Data de Término da Análise',
                'type' => 'datetime'
            ]
        ];

        $this->actions = ['viewRows'];
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
                    $this->_list[$i]['status'] = 'Em Processamento';
                    break;
                case '2':
                    $this->_list[$i]['status'] = 'Processamento Concluído';
                    break;
                case '3':
                    $this->_list[$i]['status'] = 'Analisando Arquivo Enviado';
                    break;
            }
            
        }
    }

    
    public function initContent()
    {
        parent::initContent();

        if (Tools::getIsSet('uploadCsv')) {
            $args = new AddBatchServiceArgs;

            $args->setTmpFileName($_FILES['csvfile']['tmp_name']);
            $service = $this->get(AddBatchService::class);
            $ret = $service->exec($args);
            Tools::redirectAdmin(self::$currentIndex . '&token=' . $this->token);
        }

        $tpl = $this->createTemplate('upload_file.tpl');
        $tpl->assign(['url' => $this->context->link->getPageLink('index') . '/modules/agcorreios/data/concil_example.csv']);
        $extraContent = $tpl->fetch();

        $this->content = $extraContent . $this->content;

        $this->context->smarty->assign(['content' => $this->content]);
    }

    public function displayViewRowsLink($token, $id)
    {
        $this->context->smarty->assign([
            'url' => $this->context->link->getAdminlink('AdminAgCorreiosConcilRows') . '&id_batch=' . $id
        ]);

        $tpl = $this->createTemplate('helpers/list/view_rows.tpl');
        return $tpl->fetch();
    }
}