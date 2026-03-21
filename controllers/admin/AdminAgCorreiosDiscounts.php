<?php

class AdminAgCorreiosDiscountsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap    = true;
        $this->table        = 'agcorreios_discount';
        $this->identifier   = 'id_agcorreios_discount';
        $this->className    = 'AgCorreiosDiscount';
        $this->noLink       = true;
        $this->list_no_link = true;

        parent::__construct();

        $this->module->prepareNotifications();
        if (!$this->module->auth()) {
            $this->context->controller->informations[] = 'Os descontos são aplicados apenas na versão paga do módulo.';
        }

        $selected_shop = $this->context->cookie->getAll()['shopContext'];
        $id_shop = $this->context->shop->id;
        
        if(empty($selected_shop) || strpos($selected_shop, 'g') !== false) {
           $this->context->controller->errors[] = 'Selecione a loja para criar o desconto';
        } else {

            $services = AgCorreiosServices::getAll();

            $ist_shops = Shop::getShops();

            $services_query = [];
            foreach ($services as $service) {
                $carrier = $service->getCarrier();

                $services_query[] = [
                    'id_service' => $service->id,
                    'name'       => $carrier->name
                ];
            }

            $this->_join .= ' INNER JOIN ' . _DB_PREFIX_ . 'agcorreios_services asv ON asv.id_agcorreios_services = a.id_agcorreios_service ';
            $this->_select .= ' asv.correios_name service, ';
            $this->_where = 'AND a.`id_shop` = ' . (int) $id_shop;

            $this->fields_list = [
                'id_agcorreios_discount' => [
                    'type'  => 'int',
                    'title' => 'ID',
                    'class' => 'fixed-width-sm'
                ],
                'alias' => [
                    'type'  => 'text',
                    'title' => 'Nome da Campanha',
                ],
                'service' => [
                    'type'  => 'text',
                    'title' => 'Serviço',
                    'class' => 'fixed-width-lg'
                ],
                'type_discount' => [
                    'type'       => 'select',
                    'title'      => 'Tipo de Desconto',
                    'filter_key' => 'a!type_discount',
                    'list'       => [
                        '0' => 'Percentual',
                        '1' => 'Valor Fixo'
                    ],
                    'class' => 'fixed-width-md'
                ],
                'discount' => [
                    'type'  => 'int',
                    'title' => 'Desconto',
                    'class' => 'fixed-width-sm'
                ],
                'postcode_begin' => [
                    'type'  => 'text',
                    'title' => 'CEP Início',
                    'class' => 'fixed-width-sm'
                ],
                'postcode_end' => [
                    'type'  => 'text',
                    'title' => 'CEP Fim',
                    'class' => 'fixed-width-sm'
                ],
                'cart_value_begin' => [
                    'type'  => 'price',
                    'title' => 'Pedido Mínimo',
                    'class' => 'fixed-width-sm'
                ],
                'cart_value_end' => [
                    'type'  => 'price',
                    'title' => 'Pedido Máximo',
                    'class' => 'fixed-width-sm'
                ],
                'active' => [
                    'type'   => 'bool',
                    'title'  => 'Ativo',
                    'active' => 'active'
                ]
            ];

            $this->fields_form = [
                'legend' => ['title' => 'Desconto'],
                'input'  => [
                    [
                        'name'     => 'id_agcorreios_service',
                        'type'     => 'select',
                        'label'    => 'Serviço',
                        'col'      => 3,
                        'options' => [
                            'id'    => 'id_service',
                            'name'  => 'name',
                            'query' => $services_query
                        ],
                    ],
                    [
                        'name'     => 'alias',
                        'type'     => 'text',
                        'label'    => 'Nome da Campanha',
                        'hint'     => 'Ex: Frete Grátis Sudeste',
                        'col'      => '5',
                        'required' => true
                    ],
                    [
                        'name'     => 'type_discount',
                        'type'     => 'radio',
                        'label'    => 'Tipo de desconto',
                        'required' => true,
                        'values'   => [
                            [
                                'label' => 'Percentual',
                                'id'    => 'type_discount_percentual',
                                'value' => 0
                            ],
                            [
                                'label' => 'Valor Fixo',
                                'id'    => 'type_discount_fixed_value',
                                'value' => 1
                            ]
                        ]
                    ],
                    [
                        'name'     => 'discount',
                        'type'     => 'text',
                        'label'    => 'Desconto',
                        'required' => true,
                        'col'      => 1
                    ],
                    [
                        'name'     => 'postcode_begin',
                        'type'     => 'text',
                        'label'    => 'CEP - Início',
                        'col'      => 2
                    ],
                    [
                        'name'     => 'postcode_end',
                        'type'     => 'text',
                        'label'    => 'CEP - Fim',
                        'col'      => 2
                    ],
                    [
                        'name'     => 'cart_value_begin',
                        'type'     => 'text',
                        'label'    => 'Pedido Mínimo',
                        'prefix'   => 'R$',
                        'col'      => 2
                    ],
                    [
                        'name'     => 'cart_value_end',
                        'type'     => 'text',
                        'label'    => 'Pedido Máximo',
                        'prefix'   => 'R$',
                        'col'      => 2
                    ],
                    [
                        'type' => 'switch',
                        'label' => 'Ativo',
                        'name' => 'active',
                        'values' => [
                            [
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => 'Sim',
                            ],
                            [
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => 'Não',
                            ],
                        ],
                    ]
                ],
                'submit' => [
                    'title' => 'Salvar',
                ]
            ];

            $this->actions = ['edit', 'delete'];
            $this->bulk_actions = [
                'enableSelection' => [
                    'text' => 'Ativar',
                    'icon' => 'icon-check'
                ],
                'disableSelection' => [
                    'text' => 'Desativar',
                    'icon' => 'icon-times'
                ],
                'delete' => [
                    'text' => 'Excluir',
                    'icon' => 'icon-trash'
                ]
            ];
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
        if (Tools::getIsSet('active' . $this->table)) {
            $object = $this->loadObject();
            $object->active = !$object->active;

            $object->update();

            $this->module->confirmations[]  = 'Desconto atualizado com sucesso!';
            $this->module->saveNotifications();

            Tools::redirectAdmin(self::$currentIndex);
        }

        parent::initContent();
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);

        if (is_array($this->_list)) {
            $nb = count($this->_list);
            
            for ($i = 0; $i < $nb; $i++) {
                $this->_list[$i]['type_discount'] = $this->_list[$i]['type_discount'] == 0? 'Percentual' : 'Valor Fixo';
            }
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJs(array(
            _PS_MODULE_DIR_ . 'agcorreios/views/js/jquery.mask.min.js',
            _PS_MODULE_DIR_ . 'agcorreios/views/js/admin/discounts_form.js'
        ));
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
                $object->active = (int)$status;
                if (!$object->update()) {
                    $msg_error = Db::getInstance()->getMsgError();
                    $this->module->errors[] = "Erro atualizando status do desconto {$id} - {$msg_error}";
                } else {
                    $this->module->confirmations[] = "Desconto {$id} atualizada com sucesso!";
                }
            }
        }
    }

    protected function copyFromPost(&$object, $table)
    {
        parent::copyFromPost($object, $table);
        
        $object->postcode_begin = preg_replace('/[^0-9.]+/', '', $object->postcode_begin);
        $object->postcode_end = preg_replace('/[^0-9.]+/', '', $object->postcode_end);
        $object->id_shop = $this->context->shop->id;
    }
}
