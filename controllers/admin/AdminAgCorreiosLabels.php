<?php

use AGTI\Correios\Application\Service\CriarPrePostagem;
use AGTI\Correios\Application\Service\TokenRetriever;
use AGTI\Correios\Entity\AgcorreiosTracking;
use Doctrine\ORM\EntityManagerInterface;

use AGTI\Correios\ValueObject\Configuration as VBConfiguration;

class AdminAgCorreiosLabelsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap  = true;
        $this->table      = 'agcorreios_tracking';
        $this->identifier = 'id_agcorreios_tracking';
        $this->className  = 'AgCorreiosTracking';
        $this->noLink = true;
        $this->list_no_link = true;

        parent::__construct();

        
        $this->actions = ['print'];
        $this->bulk_actions = [
            'printLabel' => [
                'text' => 'Imprimir',
                'icon' => 'icon-print'
            ]
        ];
    }

    public function init()
    {
        $this->fields_list = [
            'id_agcorreios_tracking' => [
                'title' => 'ID',
                'type' => 'int'
            ],
            'id_order' => [
                'title' => 'Pedido',
                'type' => 'int'
            ],
            'tracking_code' => [
                'title' => 'Código de Rastreio'
            ],
            'service_code' => [
                'title' => 'Código de Serviço'
            ],
            'date_add' => [
                'title' => 'Data de Criação',
                'type' => 'datetime'
            ],
            'prazo_postagem' => [
                'title' => 'Prazo de Postagem',
                'type' => 'date'
            ],
            'status_atual' => [
                'title' => 'Estado Atual',
                'type' => 'int'
            ]
        ];

        parent::init();
    }

    public function processBulkprintLabel()
    {
        $return = true;

        if (is_array($this->boxes) && !empty($this->boxes)) {
            $url = '';
            foreach ($this->boxes as $id) {
                $url .= '&ids[]=' . $id;
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules') . '&configure=agcorreios&api=printLabels' . $url);
        }
    }

    public function initContent()
    {
        parent::initContent();

        if (Tools::getIsSet('print')) {
            foreach (Tools::getValue('ids') as $id) {
                $url .= '&ids[]=' . $id;
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules') . '&configure=agcorreios&api=printLabels&ids[]=' . $url);
        }
    }
    public function displayPrintlink($token, $id)
    {
        $url = self::$currentIndex . '&token=' .  $this->token . '&print&id=' . $id;
        $tpl = $this->createTemplate('print_label.tpl');
        $tpl->assign('url', $url);
        return $tpl->fetch();
    }
}
