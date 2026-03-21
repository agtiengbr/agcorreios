<?php

use AGTI\Correios\Application\Service\BuscaServicos;
use AGTI\Correios\Application\Service\TokenRetriever;
use AGTI\Correios\ValueObject\Configuration as VBConfiguration;

class AdminAgCorreiosServicesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap  = true;
        $this->table      = 'agcorreios_services';
        $this->identifier = 'id_agcorreios_services';
        $this->className  = 'AgCorreiosServices';
        $this->noLink = true;
        $this->list_no_link = true;

        $this->_defaultOrderBy = 'carrier_name';

        parent::__construct();

    }

    public function init()
    {
        parent::init();

        $this->_select .= 'CONCAT(a.correios_code, \' - \', a.correios_name) correios_full_name, 1 as total_prices';

        $this->fields_list = [
            'id_agcorreios_services' => [
                'type' => 'int',
                'title' => 'ID',
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'carrier_name' => [
                'title' => 'Nome',
            ],
            'correios_full_name' => [
                'title' => 'Serviço',
            ],
            'weights' => [
                'title' => 'Pesos',
            ],
            'interval_mode' => [
                'title' => 'Tipo de cálculo',
                'type' => 'select',
                'list' => [
                    0 => 'Por Faixa de CEP',
                    1 => 'Por CEP único'
                ],
                'filter_key' => 'a!interval_mode',
            ],
            'shipping_method' => [
                'title' => 'Tipo de Consulta',
                'type' => 'select',
                'list' => [
                    0 => 'Tabela Local',
                    1 => 'Webservice'
                ],
                'filter_key' => 'a!shipping_method',
            ],
            'handling_time' => [
                'title' => 'Tempo de Preparação',
                'type' => 'int',
                'class' => 'fixed-width-sm'
            ],
            'enabled' => [
                'title' => 'Ativo',
                'type' => 'bool',
                'active' => 'enabled',
                'class' => 'fixed-width-xs service-active',
                'align' => 'center'
            ],
            'intervals_created' => [
                'title' => 'Tabela de Preços Criada',
                'type' => 'bool',
                'active' => 'intervals_created',
                'class' => 'fixed-width-xs service-active',
                'align' => 'center'
            ],
            'total_prices' => [
                'title' => 'Estado do Preenchimento',
                'callback' => 'calcFillment'
            ]
        ];



        //busca os serviços do contrato do cliente
        try {
            /** @var BuscaServicos */
            $s = $this->get(BuscaServicos::class);

            /** @var TokenRetriever */
            $sToken = $this->get(TokenRetriever::class);

            /** @var VBConfiguration */
            $config = $this->get(VBConfiguration::class);
            $token = $sToken->exec(
                $config->getUsername(),
                $config->getPassword(),
                $config->getCartaoPostagem()
            );

            $services = $s->exec($token);
        } catch (Exception $e) {
            $services = [];
        }
        
        if (count($services) == 0) {
            $this->warnings[] = "Não foi possível obter os serviços de postagem do seu contrato com os Correios. Por favor, se certifique de que os dados de autenticação com a API dos Correios estão corretos nas configurações do módulo.";
        }

        $services_for_query = [];
        foreach ($services as $service) {
            if ($service->getDescSegmento() == 'ENCOMENDA') {
                $services_for_query[] = [
                    'id' => $service->getCodigo(),
                    'name' => $service->getDescricao()
                ];
            }
        }

        usort($services_for_query, function($s1, $s2) {
            return strcmp($s1['name'], $s2['name']);
        });

        $this->fields_form = [
            'legend' => ['title' => 'Serviços'],
            'input'  => [
                [
                    'label' => 'Código do Serviço',
                    'name'  => 'correios_code',
                    'type'  => 'select',
                    'options' => [
                        'id' => 'id',
                        'name' => 'name',
                        'query' => @$services_for_query
                    ],
                    'col' => 2
                ],
                [
                    'label' => 'Nome do Serviço',
                    'name'  => 'correios_name',
                    'type'  => 'text',
                    'disabled' => true
                ],
                [
                    'label' => 'Nome da Transportadora',
                    'name'  => 'carrier_name',
                    'type'  => 'text',
                ],
                [
                    'label' => 'Pesos',
                    'name'  => 'weights',
                    'desc' => 'Peso em quilos. Preecha em forma crescente os pesos que delimitam as faixas utilizadas pelos Correios para definir a cobrança deste serviço. Utilize ; como separador.',
                    'type'  => 'text',
                ],
                [
                    'label' => 'Largura Máxima',
                    'name'  => 'max_depth',
                    'type' => 'text'
                ],
                [
                    'label' => 'Altura Máxima',
                    'name'  => 'max_height',
                    'type' => 'text'
                ],
                [
                    'label' => 'Comprimento Máximo',
                    'name'  => 'max_width',
                    'type' => 'text'
                ],
                [
                    'label' => 'Soma máxima das dimensões',
                    'name'  => 'max_sum_dimensions',
                    'type' => 'text'
                ],
                [
                    'type' => 'radio',
                    'label' => 'Tipo de Cálculo',
                    'name' => 'interval_mode',
                    'desc' => 'Na opção por faixa de CEP o módulo aplicará o mesmo custo e prazo de entrega a todos os CEPs de uma mesma região, aumentando a velocidade do cálculo. Na opção por CEP único, cada CEP possui seu próprio custo/prazo.',
                    'values' => [
                        [
                            'value' => 0,
                            'id' => 'interval_mode_0',
                            'label' => 'Por Faixa de CEP'
                        ],
                        [
                            'value' => 1,
                            'id' => 'interval_mode_1',
                            'label' => 'Por CEP Único'
                        ]
                    ]
                ],
                [
                    'label' => 'Tempo de Preparação',
                    'name' => 'handling_time',
                    'desc'  => 'O valor deste campo será somado ao prazo de entrega dos Correios',
                    'type' => 'text'

                ],
                [
                    'type' => 'switch',
                    'label' => 'Ativo',
                    'name' => 'enabled',
                    'values' => [
                        [
                            'id'    => 'enabled_on',
                            'value' => 1,
                            'label' => 'Sim',
                        ],
                        [
                            'id'    => 'enabled_off',
                            'value' => 0,
                            'label' => 'Não',
                        ],
                    ],
                ],
                [
                    'type' => 'radio',
                    'label' => 'Tipo de Consulta',
                    'name' => 'shipping_method',
                    'desc' => 'Utilize a opção de Tabela Local para melhor desempenho; Utilize a opção por Webservice se tiver valores incorretos apresentados no cálculo.',
                    'values' => [
                        [
                            'value' => 0,
                            'id' => 'shipping_method_0',
                            'label' => 'Tabela Local'
                        ],
                        [
                            'value' => 1,
                            'id' => 'shipping_method_1',
                            'label' => 'Webservice'
                        ]
                    ]
                ],
            ],
            'submit' => [
                'title' => 'Salvar',
            ]
        ];

        $this->actions = ['edit', 'delete', 'recalculate', 'createPrices'];
        $this->bulk_actions = [
            'enableSelection' => [
                'text' => 'Ativar',
                'icon' => 'icon-check'
            ],
            'disableSelection' => [
                'text' => 'Desativar',
                'icon' => 'icon-times'
            ]
        ];

        if (Tools::getIsSet('add' . $this->table) || Tools::getIsSet('update' . $this->table)) {
            $this->informations[] = "Para mais informações sobre cadastro de serviços personalizados, <a href='https://gitlab.com/agti/transportadoras-correios/-/wikis/Servi%C3%A7os/Cadastrando-servi%C3%A7os-personalizados' target='_blank'>clique aqui</a>.";
        } else {
            $this->informations[] = "Para mais informações sobre os serviços pré-instalados, <a href='https://gitlab.com/agti/transportadoras-correios/-/wikis/Servi%C3%A7os/Cadastrando-servi%C3%A7os-personalizados' target='_blank'>clique aqui</a>.";
        }
    }


    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        $this->page_header_toolbar_btn['configuration'] = array(
            'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->module->name,
            'desc' => 'Configurações',
            'icon' => 'process-icon- icon-cogs',
        );
    }
    
    public function initContent()
    {
        parent::initContent();
        
        if (Tools::getIsSet('enabled' . $this->table)) {
            $obj = $this->loadObject();
            $obj->enabled = !$obj->enabled;
            $obj->update();

            Tools::redirectAdmin(self::$currentIndex .'&token=' . $this->token . '&conf=4');
            exit();
        } elseif (Tools::getValue('action') == 'loadServiceData') {
            $service = new AgCorreiosServices(Tools::getValue('id'));

            $ret = [];
            $ret['weights'] = [];

            $ret['service'] = $service;
            $weights = explode(';', $service->weights);

            for ($i=1; $i<count($weights); $i++) {
                $ret['weights'][] = [
                    'from' => $weights[$i-1],
                    'to' => $weights[$i],
                    'fillment' => $service->getPercentFilledByWeightRange($weights[$i-1], $weights[$i])
                ];
            }

            echo json_encode(['success' => true, 'service' => $ret['service'], 'weights' => $ret['weights']]);
            exit();
        } elseif (Tools::getValue('action') == 'recalculate') {
            $obj = $this->loadObject();

            $obj->recalculatePrices();
            Tools::redirectAdmin(self::$currentIndex .'&token=' . $this->token . '&conf=4');
            exit();
        }
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);

        if (is_array($this->_list)) {
            $nb = count($this->_list);
            
            for ($i = 0; $i < $nb; $i++) {
                switch ($this->_list[$i]['interval_mode']) {
                    case 0:
                        $this->_list[$i]['interval_mode'] = 'Por Faixa de CEP';
                        break;
                    case 1:
                        $this->_list[$i]['interval_mode'] = 'Por CEP único';
                        break;
                }

                switch ($this->_list[$i]['shipping_method']) {
                    case 0:
                        $this->_list[$i]['shipping_method'] = 'Tabela Local';
                        break;
                    case 1:
                        $this->_list[$i]['shipping_method'] = 'Webservice';
                        break;
                }
            }
        }
    }

    /******************************* ações individuais */
    public function displayCreatePricesLink(
        $token,
        $id,
        $name
    )
    {
        $tpl = $this->createTemplate('helpers/list/create_prices.tpl');
        $tpl->assign(['url' => $this->context->link->getModuleLink('agcorreios', 'createintervals', ['id_agcorreios_service' => $id, 'force' => 1])]);
        return $tpl->fetch();
    }

    public function displayRecalculateLink(
        $token,
        $id,
        $name
    )
    {
        $tpl = $this->createTemplate('helpers/list/recalculate.tpl');
        $tpl->assign(['url' => self::$currentIndex . '&token=' . $this->token . '&action=recalculate&id_agcorreios_services=' . $id]);
        return $tpl->fetch();
    }

    /******************* ações em massa ************************/
    protected function processBulkEnableSelection()
    {
        return $this->processBulkStatusSelection(1);
    }

    protected function processBulkDisableSelection()
    {
        return $this->processBulkStatusSelection(0);
    }

    protected function processBulkStatusSelection($status)
    {
        if (is_array($this->boxes) && !empty($this->boxes)) {
            foreach ($this->boxes as $id) {
                /** @var ObjectModel $object */
                $object = new $this->className((int)$id);
                $object->enabled = (int)$status;
                if (!$object->update()) {
                    $msg_error = Db::getInstance()->getMsgError();
                    $this->module->errors[] = "Erro atualizando status do serviço {$id} - {$msg_error}";
                } else {
                    $this->module->confirmations[] = "Serviço {$id} atualizado com sucesso!";
                }
            }
        }
    }

    public function setMedia($isNewTheme=false)
    {
        parent::setMedia($isNewTheme);

        if (!Tools::getIsSet($this->identifier)) {
            $this->addJs([
                AgClienteConfig::isDebugMode() ? "https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js" : "https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js" ,
                "https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js",

                _PS_MODULE_DIR_ . 'agcliente/views/js/component/modal.js',
                _PS_MODULE_DIR_ . $this->module->name .  '/views/js/admin/services_list.js'
            ]);

            $this->addCss([
                _PS_MODULE_DIR_ . 'agcorreios/views/css/services_list.css',
                _PS_MODULE_DIR_ . 'agcliente/views/css/agmodal.css',
            ]);
        }
    }

    public function calcFillment()
    {
        $row = func_get_args()[1];

        $service = new AgCorreiosServices($row['id_agcorreios_services']);

        return $service->getPercentFilled() . '%';
    }

}