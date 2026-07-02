<?php

use AGTI\Cliente\Factory\DeliveryTimeFormatterFactory;
use AGTI\Cliente\Form\Mapping;
use AGTI\Correios\Application\Exception\BadRequestException;
use AGTI\Correios\Application\Exception\UnauthorizedException;
use AGTI\Correios\Application\Service\CriarPrePostagem;
use AGTI\Correios\Application\Service\GeraPdfEtiquetas;
use AGTI\Correios\Application\Service\TokenRetriever;
use AGTI\Correios\Entity\AgcorreiosServices;
use AGTI\Correios\Entity\AgcorreiosTracking;
use AGTI\Correios\Entity\Orders;
use AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem\AutenticaCartaoDePostagemService;
use AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem\AutenticaCartaoDePostagemServiceArgs;
use AGTI\Correios\ValueObject\Configuration as VBConfiguration;
use AGTI\Correios\ValueObject\Mappings;
use AGTI\Correios\ValueObject\SenderData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

require_once _PS_MODULE_DIR_ . 'agcliente/lib/AgCarrierModule.php';
require_once _PS_MODULE_DIR_ . 'agcorreios/vendor/autoload.php';

class BaseAgCorreios extends AgCarrierModule
{
    protected $hooks = array(
        'displayProductAdditionalInfo',
        'displayBackOfficeHeader',
        'displayHeader',
        'displayShoppingCartFooter',
        'actionAdminOrdersTrackingNumberUpdate',
        'actionObjectOrderUpdateAfter',
        'actionObjectOrderCarrierUpdateAfter',
        'displayAdminOrderTabContent',
        'displayAdminOrderTabLink',
        'displayTrackingButton'
    );

    protected $workers = [
        [
            'name' => 'createprices',
            'controller' => 'createprices',
            'delay' => 120,
            'qty_wanted_workers' => 4,
            'time_from' => '00:00',
            'time_to' => '06:00'
,
            'time_from' => '00:00',
            'time_to' => '06:00'

        ],
        [
            'name' => 'concilprices',
            'controller' => 'concilPrices',
            'delay' => 300,
            'qty_wanted_workers' => 1,
            'time_from' => '00:00',
            'time_to' => '06:00'        ,
            'time_from' => '00:00',
            'time_to' => '06:00'        
        ],
        [
            'name' => 'createConcilRows',
            'controller' => 'createConcilRows',
            'delay' => 300,
            'qty_wanted_workers' => 4,
            'time_from' => '00:00',
            'time_to' => '06:00',
            'time_from' => '00:00',
            'time_to' => '06:00'
        ],
        [
            'name' => 'rastreio',
            'controller' => 'rastreio',
            'delay' => 60
        ]
    ];

    public $id_carrier = -1;
    public $ignore_discounts = false;

    /** @var boolean Se for verdadeiro, o cálculo local será ignorado */
    public $force_webservice = false;

    //menus do administrativo
    protected $main_tab = 'AdminParentShipping';

    protected $tabs = array(
        array(
            "name"      => "Correios",
            "className" => "AdminAgCorreiosConfig",
            "active"    => 1
        ),
        array(
            "name"      => "Correios",
            "className" => "AdminAgCorreios",
            "active"    => 0,
            "childs"    => array(
                array(
                    "active"    => 1,
                    "name"      => "Serviços",
                    "className" => "AdminAgCorreiosServices",
                ),
                array(
                    "active"    => 1,
                    "name"      => "Etiquetas",
                    "className" => "AdminAgCorreiosLabels",
                ),
                array(
                    "active"    => 1,
                    "name"      => "Preços",
                    "className" => "AdminAgCorreiosPrices",
                ),
                array(
                    "active"    => 1,
                    "name"      => "Descontos",
                    "className" => "AdminAgCorreiosDiscounts",
                ),
                array(
                    "active"    => 1,
                    "name"      => "Regiões",
                    "className" => "AdminAgCorreiosInterval",
                ),
                array(
                    "active"    => 1,
                    "name"      => "Conciliação de Preços",
                    "className" => "AdminAgCorreiosConcil",
                ),
                array(
                    "active"    => 0,
                    "name"      => "Conciliação de Preços",
                    "className" => "AdminAgCorreiosConcilRows",
                )
            )
        )
    );

    protected static $cache = array();
    protected static $delay = array();

    public function __construct()
    {
        $this->name     = 'agcorreios';
        $this->tab      = 'shipping_logistics';
        $this->version  = '5.2.5';
        $this->author   = 'AGTI';

        $this->bootstrap = true;
            
        parent::__construct();

        $this->displayName = 'Transportadora Correios';
        $this->description = 'Ofereça os serviços de transportadora dos Correios em sua loja.';
    }


    public function install()
    {
        $success = parent::install();
        
        if ($success) {
            $this->createDefaultData();
        }

        if (!Configuration::hasKey('AGCORREIOS')) {
            Configuration::updateValue('AGCORREIOS', serialize($this->getDefaultOptions()));
        }

        $url = $this->context->link->getModuleLink('agcorreios', 'createintervals', ['force' => 1]);
        AgCommunicator::doCurlRequestAsync($url);


        //cria a worker para cálclo da tabela offline
        $workerGroup = new AgClienteWorkerGroup;
        $workerGroup->group_name = 'agcorreios_calc_prices';
        $workerGroup->qty_wanted_workers = 4;
        $workerGroup->module = 'agcorreios';
        $workerGroup->controller = 'CalcPrices';
        $workerGroup->active = 1;
        $workerGroup->save();

        return $success;
    }

    public function createDefaultData()
    {
        \AgCorreiosServices::uninstallServices();
        \AgCorreiosServices::installServices();
    }

    protected function renderConfigTab()
    {
        agcliente::prepareConfigHelpTab($this->name);
        $agcliente = new agcliente;


        $config_tab = $this->renderBasicConfigTab();
        $simulation_tab = $agcliente->renderShippingForm($this);
        
        $this->context->controller->addJs(array(
            '//cdn.jsdelivr.net/bluebird/3.5.0/bluebird.min.js',
            _PS_MODULE_DIR_ . $this->name . '/views/js/admin/configuration.js',
            'https://cdnjs.cloudflare.com/ajax/libs/riot/2.6.7/riot+compiler.min.js'
        ));

        $this->context->smarty->assign([
            'modules_path' => _PS_MODULE_DIR_
        ]);

        $options = $this->getOptions();

        $this->context->smarty->assign(array(
            'tabs' => [
                'config' => $config_tab,
                'additional_config' => $this->renderAdvancedConfigTab(),
                'mappign_tracking' => $this->renderMappingForm(),
                'simulation' => $simulation_tab,
                'maintenance' => agcliente::renderMaintanceTab($this)
            ],
            'is_authenticated' => true,
            'url_discounts' => $this->context->link->getAdminLink('AdminAgCorreiosDiscounts'),
            'url_concil' => $this->context->link->getAdminLink('AdminAgCorreiosConcil'),
            'url_interval' => $this->context->link->getAdminLink('AdminAgCorreiosInterval'),
            'url_prices' => $this->context->link->getAdminLink('AdminAgCorreiosPrices'),
            'url_services' => $this->context->link->getAdminLink('AdminAgCorreiosServices'),
            'url_labels' => $this->context->link->getAdminLink('AdminAgCorreiosLabels')
        ));

        if (!$options['agcorreios_zipcode_origin']) {
            $this->context->controller->errors[] = 'Você deve configurar o CEP de origem para utilizar o módulo.';
        }

        $html = $this->display(_PS_MODULE_DIR_ . $this->name, 'views/templates/admin/configuration.tpl');
        return $html;
    }

    protected function renderBasicConfigTab()
    {
        if (Tools::getIsSet('agcorreios_zipcode_origin')) {
            //salva as configurações
            Logger::addLog('Salvando configurações do módulo Correios', 1, null, 'AgCorreios', $this->id, true, $this->context->employee->id);
            
            $options = $this->getOptions();
            $zipcode_origin = Tools::getValue('agcorreios_zipcode_origin');
            ;

            if ($zipcode_origin != $options['agcorreios_zipcode_origin']) {
                Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'agcorreios_price');
            }

            $options['agcorreios_zipcode_origin'] = $zipcode_origin;
            $options['agcorreios_offline'] = Tools::getValue('agcorreios_offline');
            $options['agcorreios_precalculate'] = Tools::getValue('agcorreios_precalculate');

            $worker_group = AgClienteWorkerGroup::findByName('agcorreios_calc_prices');
            if ($worker_group->qty_wanted_workers != Tools::getValue('agcorreios_qty_processes')) {
                $worker_group->qty_wanted_workers = Tools::getValue('agcorreios_qty_processes');
                $worker_group->key_for_workers = uniqid();
                $worker_group->save();
            }

            $options['agcorreios_precalculate_qty_processes'] = Tools::getValue('agcorreios_qty_processes');

            Configuration::updateValue('AGCORREIOS_ZIPCODE_ORIGIN', Tools::getValue('agcorreios_zipcode_origin'));
            Configuration::updateValue('AGCORREIOS_PRECALCULATE', Tools::getValue('agcorreios_precalculate'));
            Configuration::updateValue('AGCORREIOS', serialize($options));



            //salva os novos dados do remente
            $sd = new SenderData;

            $sd->setShopName(Tools::getValue('AGCORREIOS_SHOP_NAME'));
            $sd->setEmail(Tools::getValue('AGCORREIOS_SHOP_MAIL'));
            $sd->setDocumentNumber(Tools::getValue('AGCORREIOS_SHOP_DOCUMENT_NUMBER'));
            $sd->setPostcode(Tools::getValue('AGCORREIOS_SHOP_POSTCODE'));
            $sd->setAddress(Tools::getValue('AGCORREIOS_SHOP_ADDRESS'));
            $sd->setAddressNumber(Tools::getValue('AGCORREIOS_SHOP_ADDRESS_NUMBER'));
            $sd->setOthers(Tools::getValue('AGCORREIOS_SHOP_ADDRESS_OTHERS'));
            $sd->setNeighborhood(Tools::getValue('AGCORREIOS_SHOP_ADDRESS_NEIGHBORHOOD'));
            $sd->setCity(Tools::getValue('AGCORREIOS_SHOP_ADDRESS_CITY'));
            $sd->setUf(Tools::getValue('AGCORREIOS_SHOP_ADDRESS_UF'));
            $sd->setDdd(Tools::getValue('AGCORREIOS_SHOP_DDD'));
            $sd->setCellphone(Tools::getValue('AGCORREIOS_SHOP_PHONE'));

            /** @var VBConfiguration */
            $config = $this->get(VBConfiguration::class);
            $config->setSenderData($sd);

            /** @var AGTI\Correios\Infrastructure\Repository\Configuration */
            $repo = $this->get(AGTI\Correios\Infrastructure\Repository\Configuration::class);
            $repo->storeConfig($config);

            $this->context->controller->confirmations[] = 'Configurações salvas com sucesso.';
        }

        $helper = $this->generateDefaultHelperForm();

        $panels = [];
        $panels[0]['form'] = [
            'legend' => [
                'title' => 'Configurações Básicas'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => 'CEP de Origem',
                    'col' => 2,
                    'name' => 'agcorreios_zipcode_origin'
                ],
                [
                    'type'   => 'switch',
                    'label'  => 'Pré-Calcular custos de Frete',
                    'name'   => 'agcorreios_precalculate',
                    'values' => array(
                        array(
                            'id'    => 'agcorreios_precalculate_on',
                            'value' => 1,
                            'label' => 'Sim',
                        ),
                        array(
                            'id'    => 'agcorreios_precalculate_off',
                            'value' => 0,
                            'label' => 'Não',
                        ),
                    ),
                ],
                [
                    'type'  => 'text',
                    'label' => 'Quantidade de processos em paralelo',
                    'name'  => 'agcorreios_qty_processes',
                    'col'   => 1,
                    'desc'  => 'Não recomendamos mais do que 2 a 4 em servidores compartilhados. Em servidores VPS recomendamos a partir de 10. Um número muito elevado poderá provocar o consumo excessivo de recursos do seu servidor.'
                ]
            ],
            'submit' => [
                'title' => 'Salvar',
                'name' => 'agcorreios-basic-config'
            ]
        ];

        $panels[1]['form'] = [
            'legend' => [
                'title' => 'Dados do Remetente das Etiquetas de Postagem'
            ],
            'input'  => array(
                array(
                    'type'        => 'text',
                    'label'       => 'Nome',
                    'name'        => 'AGCORREIOS_SHOP_NAME',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'E-mail',
                    'name'        => 'AGCORREIOS_SHOP_MAIL',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'CPF/CNPJ',
                    'name'        => 'AGCORREIOS_SHOP_DOCUMENT_NUMBER',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'CEP',
                    'name'        => 'AGCORREIOS_SHOP_POSTCODE',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'Logradouro',
                    'name'        => 'AGCORREIOS_SHOP_ADDRESS',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'Número',
                    'name'        => 'AGCORREIOS_SHOP_ADDRESS_NUMBER',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'Complemento',
                    'name'        => 'AGCORREIOS_SHOP_ADDRESS_OTHERS',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'Bairro',
                    'name'        => 'AGCORREIOS_SHOP_ADDRESS_NEIGHBORHOOD',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'Cidade',
                    'name'        => 'AGCORREIOS_SHOP_ADDRESS_CITY',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'UF',
                    'name'        => 'AGCORREIOS_SHOP_ADDRESS_UF',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'Celular (DDD)',
                    'name'        => 'AGCORREIOS_SHOP_DDD',
                ),
                array(
                    'type'        => 'text',
                    'label'       => 'Celular (Número)',
                    'name'        => 'AGCORREIOS_SHOP_PHONE',
                )
            ),
            'submit' => array(
                'title' => "Salvar",
                "name"  => "agcorreios-config-config",
            ),
        ];
        $options = $this->getOptions();

        $helper->fields_value['agcorreios_zipcode_origin'] = $options['agcorreios_zipcode_origin'];
        $helper->fields_value['agcorreios_precalculate'] = $options['agcorreios_precalculate'];
        $helper->fields_value['agcorreios_qty_processes'] = $options['agcorreios_precalculate_qty_processes'];
        $helper->fields_value['agcorreios_volume_method'] = $options['agcorreios_volume_method'];


        /** @var SenderData */
        $sd = $this->get(SenderData::class);
        $helper->fields_value['AGCORREIOS_SHOP_NAME'] = $sd->getShopName();
        $helper->fields_value['AGCORREIOS_SHOP_MAIL'] = $sd->getEmail();
        $helper->fields_value['AGCORREIOS_SHOP_DOCUMENT_NUMBER'] = $sd->getDocumentNumber();
        $helper->fields_value['AGCORREIOS_SHOP_POSTCODE'] = $sd->getPostcode();
        $helper->fields_value['AGCORREIOS_SHOP_ADDRESS'] = $sd->getAddress();
        $helper->fields_value['AGCORREIOS_SHOP_ADDRESS_NUMBER'] = $sd->getAddressNumber();
        $helper->fields_value['AGCORREIOS_SHOP_ADDRESS_OTHERS'] = $sd->getOthers();
        $helper->fields_value['AGCORREIOS_SHOP_ADDRESS_NEIGHBORHOOD'] = $sd->getNeighborhood();
        $helper->fields_value['AGCORREIOS_SHOP_ADDRESS_CITY'] = $sd->getCity();
        $helper->fields_value['AGCORREIOS_SHOP_ADDRESS_UF'] = $sd->getUf();
        $helper->fields_value['AGCORREIOS_SHOP_DDD'] = $sd->getDdd();
        $helper->fields_value['AGCORREIOS_SHOP_PHONE'] = $sd->getCellphone();

        
        return $helper->generateForm($panels);
    }

    //configurações que requerem autenticação
    protected function renderAdvancedConfigTab()
    {
        if (Tools::isSubmit('agcorreios-additional-config')) {
            $repo = $this->get(AGTI\Correios\Infrastructure\Repository\Configuration::class);

            /** @var VBConfiguration */
            $vb = $this->get(VBConfiguration::class);
            $vb->setUsername(Tools::getValue('agcorreios_username'))
                ->setPassword(Tools::getValue('agcorreios_password'))
                ->setCartaoPostagem(Tools::getValue('agcorreios_cartao_de_postagem'));

            $repo->storeConfig($vb);
            
            //obtem os demais dados do cliente
            /** @var AutenticaCartaoDePostagemService */
            $s = $this->get(AutenticaCartaoDePostagemService::class);

            $args = new AutenticaCartaoDePostagemServiceArgs;
            $args->setNumero($vb->getCartaoPostagem());

            $r = $s->exec(
                $args,
                $vb->getUsername(),
                $vb->getPassword()
            );
            
            $cartao = $r->getCartaoPostagem();
            $vb->setDrNumber($cartao->getDr())
                ->setContractNumber($cartao->getContrato())
                ->setCnpj($r->getCnpj());

            $repo->storeConfig($vb);


            $options = $this->getOptions();

            $options['agcorreios_aviso_recebimento'] = @(int)$_POST['agcorreios_aviso_recebimento'];
            $options['agcorreios_maos_proprias'] = Tools::getValue('agcorreios_maos_proprias');
            $options['agcorreios_valor_declarado'] = Tools::getValue('agcorreios_valor_declarado');

            $options['agcorreios_cartao_postagem'] = Tools::getValue('agcorreios_cartao_postagem');
            $options['agcorreios_contract_number'] = Tools::getValue('agcorreios_contract_number');
            $options['agcorreios_dr_number'] = Tools::getValue('agcorreios_dr_number');
            $options['agcorreios_username'] = Tools::getValue('agcorreios_username');
            $options['agcorreios_password'] = Tools::getValue('agcorreios_password');
            
            $options['agcorreios_volume_method'] = Tools::getValue('agcorreios_volume_method');
            Configuration::updateValue('AGCORREIOS', serialize($options));
        }

        $helper = $this->generateDefaultHelperForm();

        $panels = [];
        $panels[0]['form'] = [
            'legend' => [
                'title' => 'Configurações Adicionais'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => 'Usuário Meus Correios',
                    'col' => 2,
                    'name' => 'agcorreios_username'
                ],
                [
                    'type' => 'text',
                    'label' => 'Código de acesso API',
                    'desc' => 'Para obter o seu código, acesse <a target="_blank" href="https://cws.correios.com.br/acesso-componentes">https://cws.correios.com.br/acesso-componentes</a>. ',
                    'col' => 2,
                    'name' => 'agcorreios_password'
                ],
                [
                    'type' => 'text',
                    'label' => 'Cartão de Postagem',
                    'col' => 2,
                    'name' => 'agcorreios_cartao_de_postagem'
                ],
                [
                    'type' => 'text',
                    'label' => 'CNPJ',
                    'name' => 'agcorreios_cnpj',
                    'disabled' => true,
                    'hint' => 'Esse campo será obtido automaticamente se os demais forem prenchidos corretamente',
                    'col' => 3
                ],
                [
                    'type' => 'text',
                    'label' => 'Número do Contrato',
                    'name' => 'agcorreios_contract_number',
                    'disabled' => true,
                    'hint' => 'Esse campo será obtido automaticamente se os demais forem prenchidos corretamente',
                    'col' => 3
                ],
                [
                    'type' => 'text',
                    'label' => 'Número da DR',
                    'col' => 2,
                    'name' => 'agcorreios_dr_number',
                    'disabled' => true,
                    'hint' => 'Esse campo será obtido automaticamente se os demais forem prenchidos corretamente',
                    'col' => 3
                ],
                [
                    'type'   => 'switch',
                    'label'  => 'Aviso de Recebimento',
                    'name'   => 'agcorreios_aviso_recebimento',
                    'values' => array(
                        array(
                            'id'    => 'agcorreios_aviso_recebimento_on',
                            'value' => 1,
                            'label' => 'Sim',
                        ),
                        array(
                            'id'    => 'agcorreios_aviso_recebimento_off',
                            'value' => 0,
                            'label' => 'Não',
                        ),
                    ),
                ],
                [
                    'type'   => 'switch',
                    'label'  => 'Mãos Próprias',
                    'name'   => 'agcorreios_maos_proprias',
                    'values' => array(
                        array(
                            'id'    => 'agcorreios_maos_proprias_on',
                            'value' => 1,
                            'label' => 'Sim',
                        ),
                        array(
                            'id'    => 'agcorreios_maos_proprias_off',
                            'value' => 0,
                            'label' => 'Não',
                        ),
                    ),
                ],
                [
                    'type'   => 'switch',
                    'label'  => 'Valor Declarado',
                    'name'   => 'agcorreios_valor_declarado',
                    'values' => array(
                        array(
                            'id'    => 'agcorreios_valor_declarado_on',
                            'value' => 1,
                            'label' => 'Sim',
                        ),
                        array(
                            'id'    => 'agcorreios_valor_declarado_off',
                            'value' => 0,
                            'label' => 'Não',
                        ),
                    ),
                ],
                [
                    'type'   => 'radio',
                    'label'  => 'Método de Cubagem',
                    'name'   => 'agcorreios_volume_method',
                    'values' => array(
                        array(
                            'id'    => 'agcorreios_volume_method_on',
                            'value' => 1,
                            'label' => 'Considerar dimensões do maior produto',
                        ),
                        array(
                            'id'    => 'agcorreios_volume_method_off',
                            'value' => 0,
                            'label' => 'Considerar todos os produtos',
                        ),
                    ),
                ],
            ],
            'submit' => [
                'title' => 'Salvar',
                'name' => 'agcorreios-additional-config'
            ]
        ];

        $options = $this->getOptions();

        $config = $this->get(VBConfiguration::class);
        

        $helper->fields_value['agcorreios_maos_proprias'] = $options['agcorreios_maos_proprias'];
        $helper->fields_value['agcorreios_aviso_recebimento'] = $options['agcorreios_aviso_recebimento'];

        $helper->fields_value['agcorreios_valor_declarado'] = $options['agcorreios_valor_declarado'];
        $helper->fields_value['agcorreios_volume_method'] = $options['agcorreios_volume_method'];
        
        $helper->fields_value['agcorreios_username'] = $config->getUsername();
        $helper->fields_value['agcorreios_password'] = $config->getPassword();

        $helper->fields_value['agcorreios_cartao_de_postagem'] = $config->getCartaoPostagem();
        $helper->fields_value['agcorreios_contract_number'] = $config->getContractNumber();
        $helper->fields_value['agcorreios_dr_number'] = $config->getDrNumber();
        $helper->fields_value['agcorreios_cnpj'] = $config->getCnpj();


        return $helper->generateForm($panels);
    }

    public function getContent()
    {
        if (Tools::getIsSet('api')) {
            $method_name = 'api' . ucFirst(Tools::getValue('api'));
            
            if (!method_exists($this, $method_name)) {
                http_response_code(404);
                exit();
            }

            $this->{$method_name}();
        }

        $this->context->controller->addJs(array(
            _PS_MODULE_DIR_ . $this->name . '/views/js/jquery.mask.min.js',
            _PS_MODULE_DIR_ . $this->name . '/views/js/admin/config.js'
        ));

        if (Tools::getIsSet('check_auth')) {
            echo json_encode([
                'success' => true,
                'is_authenticated' => true
            ]);
            exit();
        }
        return $this->renderConfigTab();
    }

    public function hookDisplayBackOfficeHeader()
    {
        $controllers = [
            'AdminAgCorreiosDiscountsController',
            'AdminAgCorreiosPricesController',
            'AdminAgCorreiosIntervalController',
            'AdminAgCorreiosServicesController'
        ];

        $display_config_errors = in_array(get_class($this->context->controller), $controllers);
        $display_config_errors |= $this->context->controller instanceof AdminModules && !Tools::getIsSet('configure');

        $errors = $this->validateConfiguration();
        if ($display_config_errors && count($errors)) {
            $this->context->controller->errors[] = 'Transportadora Correios - O módulo não está corretamente configurado para que as transportadoras sejam exibidas para seus clientes. <br/>' . implode("<br/>", $errors);
        }

        Media::addJsDef(array(
            'agcorreios_base_uri' => $this->context->link->getAdminLink('AdminModules') . "&configure={$this->name}",
        ));

        $this->context->controller->addJs(array(
            _PS_MODULE_DIR_ . $this->name . '/views/js/admin/order_labels_managment.js',
        ));
        $this->context->controller->addCss(array(
            _PS_MODULE_DIR_ . $this->name . '/views/css/adminStyle.css',
        ));
    }

    protected static function getCacheKey($postcode, $correios_code, $height, $width, $depth, $weight, $ignore_discounts)
    {
        return 'agcorreios_price_'  . implode('_', array($postcode, $correios_code, $height, $width, $depth, $weight, (int)$ignore_discounts));
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
        $service = \AgCorreiosServices::getByCarrier($this->id_carrier);
        if (!Validate::isLoadedObject($service)) {
            return false;
        }

        $address = new Address($params->id_address_delivery);

        $product_value = 0;
        $weight = 0;
        $products = array();

        foreach ($params->getProducts() as $product) {
            if ($product['is_virtual']) {
                continue;
            }

            $products[] = array(
                'height' => $product['height'],
                'width' => $product['width'],
                'depth' => $product['depth'],
                'quantity' => $product['cart_quantity']
            );
            
            $weight += $product["weight"] * $product["cart_quantity"];
            $product_value += $product["price_wt"] * $product["cart_quantity"];
        }
        if (count($products) == 0) {
            return;
        }

        $dimensions = $this->getDimensions($products);

        $postcode = $address->postcode;
        if (!$postcode) {
            return false;
        }

        $options = $this->getOptions();
        $return = $this->calcShippingCost(
            $postcode,
            $weight,
            $dimensions,
            $product_value,
            $options['agcorreios_valor_declarado'] ? $product_value : 0
        );

        if ($return === false) {
            return false;
        }

        return $return + $shipping_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
    }

    public function simulateAllCarriersForProduct($postcode, $id_product, $id_product_attribute = 0, $quantity = 1)
    {
        $prices = array();

        $services = \AgCorreiosServices::getAll();

        $product          = new Product($id_product);
        $product_carriers = $product->getCarriers();

        foreach ($services as $service) {
            $carrier = Carrier::getCarrierByReference($service->carrier_id);
            $this->id_carrier = $carrier->id;

            if ($carrier->deleted || !$carrier->active) {
                continue;
            }

            //verifica se o produto pode ser enviado pela transportadora do serviço
            $is_available = false;
            foreach ($product_carriers as $product_carrier) {
                if ($product_carrier['id_reference'] == $service->carrier_id) {
                    $is_available = true;
                }
            }

            if (!$is_available && count($product_carriers)) {
                continue;
            }
            $price = $this->calcShippingCostForProduct(
                $postcode,
                $id_product,
                $id_product_attribute,
                $quantity
            );

            if ($price === false) {
                continue;
            }


            $data = array(
                'carrier' => $carrier,
                'delay' => self::getDelay($carrier->id)
            );
            if ($this->ps17 || $this->ps8) {
                $price_formatter = new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter();
                $data['price'] = $price_formatter->convertAndFormat($price);
            } else {
                $data['price'] =Tools::displayPrice($price);
            }

            $prices[] = $data;
        }

        return $prices;
    }

    public function simulateAllCarriersForDimensions($postcode, $weight, $dimensions, $carriers_available = [], $value = 0, $valor_declarado = 0)
    {
        $prices = array();

        $services = \AgCorreiosServices::getAll();
        foreach ($services as $service) {
            $carrier = Carrier::getCarrierByReference($service->carrier_id);
            if (!in_array($carrier->id, $carriers_available)) {
                continue;
            }

            $this->id_carrier = $carrier->id;

            if ($carrier->deleted || !$carrier->active) {
                continue;
            }

            $price = str_replace(',', '.', $this->calcShippingCost(
                $postcode,
                $weight,
                $dimensions,
                $value,
                $valor_declarado
            ));
            
            if ($price === false || $price === "") {
                continue;
            }

            $price += Configuration::get('PS_SHIPPING_HANDLING');

            if ($this->ps17 || $this->ps8) {
                $price_formatter = new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter();
                $formated_price = $price_formatter->convertAndFormat($price);
            } else {
                $formated_price = Tools::displayPrice($price);
            }


            $prices[] = array(
                'carrier' => $carrier,
                'price' => $formated_price,
                'price_unformatted' => $price,
                'delay' => self::getDelay($carrier->id)
            );
        }

        return $prices;
    }

    //retorna as dimensões em centímetros
    public function getDimensions($products)
    {
        usort($products, function ($p1, $p2) {
            return $p1['width'] * $p1['depth'] * $p1['height'] - $p2['width'] * $p2['depth'] * $p2['height'];
        });

        $options = $this->getOptions();
        //utiliza o produto de maior volume
        if (@$options['agcorreios_volume_method'] == 1) {
            $index = count($products) - 1;
            return [
                $products[$index]['height'],
                $products[$index]['width'],
                $products[$index]['depth']
            ];
        }

        $return = [0, 0, 0];

        $volume = 0;

        //no caso de múltiplas dimensões, cria vários produtos individuais
        $products_to_use = [];
        foreach ($products as $product) {
            $volume += $product["height"] * $product["width"] * $product["depth"] * $product['quantity'];
        }

        $return[0] = $return[1] = $return[2] = pow($volume, 1/3);
        return $return;
    }

    public function calcShippingCostForProduct($postcode, $id_product, $id_product_attribute = 0, $quantity = 1)
    {
        $product = new Product($id_product);

        $dimensions = $this->getDimensions(array(
            array(
                'height' => $product->height,
                'width' => $product->width,
                'depth' => $product->depth,
                'quantity' => $quantity
            )
        ));

        $options = $this->getOptions();

        $price = $this->calcShippingCost(
            $postcode,
            $product->weight * $quantity,
            $dimensions,
            $quantity * $product->getPrice(true, $id_product_attribute),
            $options['agcorreios_valor_declarado'] ? $quantity * $product->getPrice(true, $id_product_attribute) : 0
        );

        if ($price !== false) {
            return $price + Configuration::get('PS_SHIPPING_HANDLING');
        } else {
            return false;
        }
    }

    public function simulateAllCarriersForCart(Cart $cart, $postcode=false)
    {
        $product_value = 0;
        $weight = 0;
        $products = array();
        $products_ids = [];


        $carriers = [];
        $services = \AgCorreiosServices::getAll();

        foreach ($services as $service) {
            $carrier = Carrier::getCarrierByReference($service->carrier_id);
            $carriers[] = $carrier->id;
        }

        $carriers = array_unique($carriers);

        foreach ($cart->getProducts() as $product) {
            if ($product['is_virtual']) {
                continue;
            }

            $obj = new Product($product['id_product']);

            $product_carriers = [];
            foreach ($obj->getCarriers() as $product_carrier) {
                $product_carriers[] = $product_carrier['id_reference'];
            }

            if (count($product_carriers)) {
                $carriers = array_intersect($carriers, $product_carriers);
            }

            $products[] = array(
                'height' => $product['height'],
                'width' => $product['width'],
                'depth' => $product['depth'],
                'quantity' => $product['cart_quantity']
            );
            
            $weight += $product["weight"] * $product["cart_quantity"];
            $product_value += $product["price_wt"] * $product["cart_quantity"];
        }

        if (count($products) == 0) {
            return [];
        }

        $dimensions = $this->getDimensions($products);
        $address = new Address($cart->id_address_delivery);

        $options = $this->getOptions();
        
        $return = $this->simulateAllCarriersForDimensions(
            $postcode? $postcode : $address->postcode,
            $weight,
            $dimensions,
            $carriers,
            $product_value,
            $options['agcorreios_valor_declarado']? $product_value : 0
        );

        return $return;
    }

    public function calcShippingCost($postcode, $weight, $dimensions, $product_value, $valor_declarado)
    {
        $options = $this->getOptions();
        $id_shop = $this->context->shop->id;

        if (trim($postcode) == '') {
            return false;
        }

        //conversão de unidade
        if (Configuration::get('PS_WEIGHT_UNIT') == 'g') {
            $weight /= 1000;
        }
        
        //valor declarado não pode ser inferior a 18.50
        if ($valor_declarado) {
            $valor_declarado = max($valor_declarado, 18.50);
        }

        $postcode = preg_replace('/[^0-9.]+/', '', $postcode);
        $this->context->cookie->agcorreios_postcode = $postcode;

        try {
            //serviço dos correios
            $service = \AgCorreiosServices::getByCarrier($this->id_carrier);
            if (!Validate::isLoadedObject($service)) {
                return false;
            }

            //pacote
            $cache_key = self::getCacheKey($postcode, $service->correios_code, $dimensions[2], $dimensions[1], $dimensions[0], $weight, $this->ignore_discounts);
            if (isset(self::$cache[$cache_key])) {
                if (self::$cache[$cache_key] === false) {
                    return false;
                }

                return self::$cache[$cache_key]['total'];
            }
            self::$cache[$cache_key] = false;


            $options = $this->getOptions();


            //calcula o peso cúbico (VER NGCOR-98)
            $width  = max($dimensions[1], 10);
            $depth  = max($dimensions[2], 10);
            $height = max($dimensions[0], 2);

            if ($width > $service->max_width || $height > $service->max_height || $depth > $service->max_depth || $width + $height + $depth > $service->max_sum_dimensions) {
                return false;
            }

            $weight = max($weight, 0.1);

            $volume = $width * $height * $depth;
            $cubic_weight = $volume / 6000;
            if ($cubic_weight > 5) {
                $weight_offline = max($weight, $cubic_weight);
            } else {
                $weight_offline = $weight;
            }

            if ($service->shipping_method == 0) {
                $price = AgCorreiosPrices::get(
                    $postcode,
                    $weight_offline,
                    $service->id,
                    $this->context->shop->id
                );
            }

            //todos os parâmetros estão ok. se ocorrer algum erro aqui, realiza a consulta online e salva ela na tabela
            // try {
            if ($this->force_webservice || !Validate::isLoadedObject(@$price) || $price->recalculate) {
                $price_remote = AgCorreiosPrices::calcExternal(
                    $service->correios_code,
                    $options['agcorreios_zipcode_origin'],
                    $postcode,
                    $weight,
                    $height,
                    $width,
                    $depth,
                    $valor_declarado,
                    $options['agcorreios_aviso_recebimento'],
                    $options['agcorreios_maos_proprias'],
                    !$this->force_webservice,
                    false,
                    $options['agcorreios_contract_number'],
                    $options['agcorreios_contract_password']
                );

                $price = new AgCorreiosPrices;
                $price->shipping_cost = $price_remote['price'];
                $price->delivery_time = $price_remote['delay'];
            }

            if (!$price->shipping_cost) {
                return false;
            }

            if (Validate::isLoadedObject($price) && $options['agcorreios_aviso_recebimento']) {
                $price->price += 4.30;
            }

            if (Validate::isLoadedObject($price) &&  $options['agcorreios_maos_proprias']) {
                $price->price += 5.90;
            }

            //seguro valor declarado
            if (Validate::isLoadedObject($price) &&  $options['agcorreios_valor_declarado']) {
                $price->shipping_cost += max($valor_declarado - 18.50, 0) * 0.015;
            }

            if (Validate::isLoadedObject($price) && max($width, $height, $depth) > 70) {
                $price->shipping_cost += 79;
            }


            if (!$this->ignore_discounts) {
                $discount = AgCorreiosDiscount::getDiscountByPostcodeAndPrice($postcode, $product_value, $service->id, $id_shop);
                if (Validate::isLoadedObject($discount)) {
                    $price->shipping_cost = $discount->applyTo($price->shipping_cost);
                }
            }

            self::$cache[$cache_key] = array('total' => $price->shipping_cost, 'prazo' => $price->delivery_time);

            //verifica se o recurso novo de formatar o prazo de entrega está disponível
            require_once _PS_MODULE_DIR_ . 'agcliente/agcliente.php';
            $agcliente = new agcliente;

            if (version_compare($agcliente->version, '1.13.0', '>=')) {
                $formatter = DeliveryTimeFormatterFactory::createFormatter(Configuration::get('AGTI_SIMULATION_DELIVERY_DATE_MODE'));
                self::$delay[$this->id_carrier] = $formatter->format($price->delivery_time + $service->handling_time, Configuration::get('AGTI_SIMULATION_DELIVERY_DATE_CUSTOM_FORMAT'));
            } else {
                self::$delay[$this->id_carrier] = 'Até ' . ($price->delivery_time + $service->handling_time) . ' dias úteis.';
            }

            return $price->shipping_cost;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getDelay($id_carrier)
    {
        if (isset(self::$delay[$id_carrier])) {
            return self::$delay[$id_carrier];
        }

        return false;
    }

    public function getOptions()
    {
        $sql = new DbQuery;

        $sql->from('configuration')
            ->select('value')
            ->where('name="AGCORREIOS"');

        if (Shop::isFeatureActive()) {
            $sql->where('id_shop=' . (int)$this->context->shop->id);
            $sql->where('id_shop_group=' . (int)$this->context->shop->id_shop_group);
        }

        $options = Db::getInstance()->getValue($sql);
        if (empty($options)) {
            $options = $this->getDefaultOptions();
        } else {
            $options = unserialize($options);
        }

        $options['agcorreios_precalculate'] = Configuration::get('AGCORREIOS_PRECALCULATE');
        return $options;
    }

    protected function getUnauthenticatedOptions()
    {
        $worker_group = AgClienteWorkerGroup::findByName('agcorreios_calc_prices');

        $return = [
            'agcorreios_contract_number' => '',
            'agcorreios_contract_password' => '',
            'agcorreios_aviso_recebimento' => false,
            'agcorreios_maos_proprias' => false,
            'agcorreios_valor_declarado' => false,
            'agcorreios_offline' => false,
            'agcorreios_zipcode_origin' => Configuration::get('AGCORREIOS_ZIPCODE_ORIGIN'),
            'agcorreios_precalculate' => Configuration::get('AGCORREIOS_PRECALCULATE'),
            'agcorreios_precalculate_qty_processes' => Validate::isLoadedObject($worker_group) ? $worker_group->qty_wanted_workers : 2,
            'agcorreios_volume_method' => 0
        ];

        $options = Configuration::get('AGCORREIOS');
        $options = unserialize($options);

        $return = array_merge($options, $return);

        return $return;
    }

    protected function getDefaultOptions()
    {
        $worker_group = AgClienteWorkerGroup::findByName('agcorreios_calc_prices');

        return [
            'agcorreios_contract_number' => '',
            'agcorreios_contract_password' => '',
            'agcorreios_aviso_recebimento' => false,
            'agcorreios_maos_proprias' => false,
            'agcorreios_valor_declarado' => false,
            'agcorreios_offline' => false,
            'agcorreios_zipcode_origin' => Configuration::get('AGCORREIOS_ZIPCODE_ORIGIN'),
            'agcorreios_precalculate' => Configuration::get('AGCORREIOS_PRECALCULATE'),
            'agcorreios_precalculate_qty_processes' => Validate::isLoadedObject($worker_group) ? $worker_group->qty_wanted_workers : 2,
            'agcorreios_volume_method' => 0
        ];
    }


    public function validateConfiguration()
    {
        $options = $this->getOptions();

        $errors = [];
        if (!$options['agcorreios_zipcode_origin']) {
            $errors[] = 'O CEP de origem não está corretamente configurado.';
        }

        return $errors;
    }


    public function getPackageShippingCost($cart, $shipping_cost, $products)
    {
        $address = new Address($cart->id_address_delivery);
        $options = $this->getOptions();

        $cart_value = 0;

        $service = \AgCorreiosServices::getByCarrier($this->id_carrier);
        if (!Validate::isLoadedObject($service)) {
            return false;
        }


        foreach ($products as $product) {
            $cart_value += $product['total_wt'];
        }

        if (isset($cart->postcode_origin) && $cart->postcode_origin != '-1') {
            if (!$cart->postcode_origin) {
                return false;
            }
        } elseif (Module::isEnabled('agmarketplace')) {
            require_once _PS_MODULE_DIR_ . 'agmarketplace/classes/AgMarketplaceProduct.php';
            //verifica se carrinho produto pertence a um seller do marketplace ou é do admin
            $is_seller = false;
            foreach ($products as $product) {
                $seller = (new AgMarketplaceProduct)->findSellerByPsProduct(new Product($product['id_product']));
                if (Validate::isLoadedObject($seller)) {
                    $is_seller = true;
                    break;
                }
            }

            if ($is_seller) {
                return false;
            }

            $zipcode_origin = $options['agcorreios_zipcode_origin'];
            $cart->postcode_origin = preg_replace("/[^0-9]/", "", $zipcode_origin);
        }

        $weight = 0;
        foreach ($products as $i => $product) {
            $products[$i]['id'] = $product['id_product'] . '-' . $product['id_product_attribute'];
            $products[$i]['weight'] = max($product['weight'], 0.001);

            if (isset($product['cart_quantity_fractional'])) {
                $weight += $products[$i]["weight"] * $product['cart_quantity_fractional'];
            } else {
                $weight += $products[$i]["weight"] * $product['cart_quantity'];
            }
        }

        $dimensions = $this->getDimensions($products);
        $address = new Address($cart->id_address_delivery);

        $return = $this->simulateAllCarriersForDimensions(
            $address->postcode,
            $weight,
            $dimensions,
            [$this->id_carrier],
            $cart_value,
            $options['agcorreios_valor_declarado']? $product_value : 0
        );

        if (!count($return)) {
            return false;
        }

        $price = $return[0]['price_unformatted'];
        return $price;
    }


    public static function getCombinationWeight($id_product_attribute, $id_shop)
    {
        $sql = new DbQuery;
        $sql->from('product_attribute_shop')
            ->where('id_product_attribute=' . (int) $id_product_attribute)
            ->where('id_shop=' . (int) $id_shop)
            ->select('weight');

        $consult = Db::getInstance()->getValue($sql);

        if ($consult === false) {
            $sql = new DbQuery;
            $sql->from('product_attribute')
                ->select('weight')
                ->where('id_product_attribute=' . (int)$id_product_attribute);

            $consult = Db::getInstance()->getValue($sql);
        }

        return $consult;
    }

    public function HookActionAdminOrdersTrackingNumberUpdate($props)
    {
        try {
            if($props['carrier']->external_module_name == 'agcorreios'){
                $order = $props['order'];
                $carrier = $props['carrier'];

                $trackings = \AgCorreiosTracking::getByOrder($order->id);
            
                foreach ($trackings as $tracking) {
                    $tracking->finished = 1;
                    $tracking->save();
                }
                $tracking = new \AgCorreiosTracking();
                $tracking->id_order = $order->id;
                $tracking->id_carrier = $carrier->id;
                $tracking->tracking_code = $order->shipping_number;
                $tracking->finished = 0;
                $tracking->save();
            }
        } catch (Exception $e) {
        }

    }

    protected function renderMappingForm()
    {
        /** @var Mappings */
        $mappings = $this->get(Mappings::class);

        $mapping = new Mapping($this);

        //mapeamento de campos
        $mapping->addPanel(
            'Mapeamento de Campos',
            // new PersonTypeMapping(),
            $mappings->getCpf(),
            $mappings->getRg(),
            $mappings->getCompanyName(),
            $mappings->getCnpj(),
            $mappings->getIe(),
            $mappings->getAddressNumber()
        );

        $mapping->postProcess();
        $html = $mapping->renderHtml();



        //mapeamento de estados
        $eventTypes = AgCorreiosTrackingEvents::getEventTypes();
        $options = $this->getOptions();
        $events = [
            ['desc' => 'Objeto entregue ao destinatário'],
            ['desc' => 'Objeto aguardando retirada no endereço indicado'],
            ['desc' => 'Objeto em trânsito - por favor aguarde'],
            ['desc' => 'Pagamento confirmado'],
            ['desc' => 'Fiscalização aduaneira concluída - aguardando pagamento'],
            ['desc' => 'Encaminhado para fiscalização aduaneira'],
            ['desc' => 'Objeto recebido pelos Correios do Brasil'],
            ['desc' => 'Objeto postado'],
            ['desc' => 'Objeto saiu para entrega ao destinatário'],
            ['desc' => 'Objeto entregue ao remetente'],
            ['desc' => 'Objeto será entregue em instantes'],
            ['desc' => 'Objeto saiu para entrega ao remetente'],
            ['desc' => 'Prazo de retirada pelo destinatário encerrado'],
            ['desc' => 'Objeto não entregue - carteiro não atendido'],
            ['desc' => 'Objeto recebido na unidade de distribuição'],
            ['desc' => 'Objeto não entregue - endereço incorreto'],
            ['desc' => 'Objeto postado após o horário limite da unidade'],
            ['desc' => 'Objeto disponível para retirada em Caixa Postal'],
            ['desc' => 'Objeto está em rota de entrega'],
            ['desc' => 'Objeto não entregue - cliente desconhecido no local'],
            ['desc' => 'Objeto entregue na Caixa de Correios Inteligente'],
            ['desc' => 'Tentativa de entrega não efetuada'],
            ['desc' => 'Objeto não entregue - Endereço não encontrado'],
            ['desc' => 'Objeto encaminhado para retirada no endereço indicado'],
            ['desc' => 'Saída para entrega cancelada']
        ];

        if (Tools::isSubmit('agcorreios-mapping-tracking-config')) {
            
            foreach ($events as $event) {
                $options['AGCORREIOS_TRACKING_EVENT_'.$this->formatString($event['desc'])] = Tools::getValue('AGCORREIOS_TRACKING_EVENT_'.$this->formatString($event['desc']));
            }
            foreach ($eventTypes as $eventType) {
                $descEvents = array_column($events, 'desc');
                if(!in_array($eventType['desc'],$descEvents)){
                    $options['AGCORREIOS_TRACKING_EVENT_'.$this->formatString($eventType['desc'])] = Tools::getValue('AGCORREIOS_TRACKING_EVENT_'.$this->formatString($eventType['desc']));
                }
            }

            $options['AGCORREIOS_TRACKING_EVENT_FINISHED'] = implode(", ", Tools::getValue('AGCORREIOS_TRACKING_EVENT_FINISHED_selected'));
        
            Configuration::updateValue('AGCORREIOS', serialize($options));
        
        }

        $helper = $this->generateDefaultHelperForm();
        $inputs = [];

        // eventos já conhecidos
        $orderState = OrderState::getOrderStates($this->context->language->id);
        usort($orderState, function($s1, $s2) {
            return strcmp($s1['name'], $s2['name']);
        });

        $orderStateFromSelect[] = ['id' => -1, 'name' => 'Não atualizar pedido nesse estado'];
        foreach ($orderState as $orderState) {
            $orderStateFromSelect[] = [
                'id' => $orderState['id_order_state'],
                'name' => $orderState['name']
            ];
        }

        foreach ($events as $event) {
            $inputs[] = [
                'label' => $event['desc'],
                'name' => 'AGCORREIOS_TRACKING_EVENT_'.$this->formatString($event['desc']),
                'type' => 'select',
                'col' => 8,
                'options' => [
                    'id' => 'id',
                    'name' => 'name',
                    'query' => $orderStateFromSelect
                ]
            ];
            $helper->fields_value['AGCORREIOS_TRACKING_EVENT_'.$this->formatString($event['desc'])] = @$options['AGCORREIOS_TRACKING_EVENT_'.$this->formatString($event['desc'])];
        }


        // eventos não conhecidos
        foreach ($eventTypes as $eventType) {
            // se o evento não for conhecido
            $descEvents = array_column($events, 'desc');
            if(!in_array($eventType['desc'],$descEvents)){
                $inputs[] = [
                    'label' => $eventType['desc'],
                    'name' => 'AGCORREIOS_TRACKING_EVENT_'.$this->formatString($eventType['desc']),
                    'type' => 'select',
                    'col' => 8,
                    'options' => [
                        'id' => 'id',
                        'name' => 'name',
                        'query' => $orderStateFromSelect
                    ]
                ];
                $events[] = ['desc' => $eventType['desc']];
                $helper->fields_value['AGCORREIOS_TRACKING_EVENT_'.$this->formatString($eventType['desc'])] = $options['AGCORREIOS_TRACKING_EVENT_'.$this->formatString($eventType['desc'])];
            }
        }

        // ordena os inputs de acordo com a label
        usort($inputs, function($a, $b) {
            return strcmp($a['label'], $b['label']);
        });


        usort($events, function($e1, $e2) {
            return strcmp($e1['desc'], $e2['desc']);
        });

        // coloca o campo como primeiro
        array_unshift($inputs, [
            'type' => 'swap',
            'label' => "Estados de Finalização da entrega",
            'name' => 'AGCORREIOS_TRACKING_EVENT_FINISHED',
            'required' => false,
            'multiple' => true,
            'size' => 10,
            'desc' => 'Quando uma etiqueta chegar a um dos estados acima, a integração parará de rastrea-la. Ou seja, os estados acima são os estados finais do acompanhamento da entrega.',
            'options' => [
                'query' =>  $events,
                'id' => 'desc',
                'name' => 'desc'
            ]
        ]);



        $panels[1]['form'] = [
            'legend' => [
                'title' => 'Rastreamento'
            ],
            
            'description' => 'Essas configurações requerem que você possua uma licença válida. Elas não estão disponíveis na versão gratuita do módulo.',
            'input' => $inputs,
            'submit' => [
                'title' => 'Salvar',
                'name' => 'agcorreios-mapping-tracking-config'
            ]
        ];
        $helper->fields_value['AGCORREIOS_TRACKING_EVENT_FINISHED'] = explode(', ', $options['AGCORREIOS_TRACKING_EVENT_FINISHED']);

        return $html .  $helper->generateForm($panels);
    }

    public function formatString($str) {
        $str = strtoupper($str);
    
        $str = preg_replace('/\s+/', '', $str);
    
        $str = preg_replace('/[^a-zA-Z0-9]/', '', $str);
    
        return $str;
    }




    private function apiCreateLabel()
    {
        try {
            $idOrder = Tools::getValue('id_order');

            /** @var EntityManagerInterface */
            $em = $this->get('doctrine.orm.entity_manager');
            
            $order = $em->getRepository(Orders::class)->findOneBy(['id' => $idOrder]);
            if (is_null($order)) {
                throw new Exception("Pedido não localizado.");
            }

            
            /** @var TokenRetriever */
            $sToken = $this->get(TokenRetriever::class);

            /** @var VBConfiguration */
            $config = $this->get(VBConfiguration::class);
            $token = $sToken->exec(
                $config->getUsername(),
                $config->getPassword(),
                $config->getCartaoPostagem()
            );        

            /** @var CriarPrePostagem */
            $s = $this->get(CriarPrePostagem::class);
            $s->exec($order, Tools::getValue('service_code'), $token->getToken(), Tools::getIsSet('coleta'),
            Tools::getIsSet('reversa'));

            echo json_encode(['success' => true]);
        } catch (BadRequestException $e) {
            echo json_encode(['success' => false, 'error' => 'A API dos Correios rejeitou um ou mais parâmetros com o erro: ' . $e->getApiMessage()]);
        } catch (UnauthorizedException $e) {
            echo json_encode(['success' => false, 'error' => 'Erro de autenticação com a API dos Correios. Verifique se os dados configurados no módulo estão corretos.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit();
    }

    private function apiPrintLabels()
    {
        $ids = Tools::getValue('ids');

        /** @var EntityManagerInterface */
        $em = $this->get('doctrine.orm.entity_manager');
        
        /** @var AgcorreiosTracking[] */
        $labels = $em->getRepository(AgcorreiosTracking::class)->findBy(['id' => $ids]);

        /** @var TokenRetriever */
        $sToken = $this->get(TokenRetriever::class);

        /** @var VBConfiguration */
        $config = $this->get(VBConfiguration::class);
        $token = $sToken->exec(
            $config->getUsername(),
            $config->getPassword(),
            $config->getCartaoPostagem()
        );        

        /** @var GeraPdfEtiquetas */
        $s = $this->get(GeraPdfEtiquetas::class);
        $f = $s->exec($labels, $token);

        $filename = stream_get_meta_data($f)['uri'];
            
        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="etiquetas.pdf"');
        header('content-Transfer-Encoding:binary');
        header('Accept-Ranges:bytes');
        @readfile($filename);

        fclose($f);

        exit();
    }


    private function apiGetServices()
    {
        /** @var EntityManagerInterface */
        $em = $this->get('doctrine.orm.entity_manager');

        $services = $em->getRepository(AgcorreiosServices::class)->findAll();
        
        /** @var SymfonySerializer */
        $serializer = $this->get('agti.correios.infrastructure.serializer.serializer');
        echo $serializer->serialize([
            'success' => true,
            'services' => $services
        ], 'json');

        exit();
    }

    public function hookDisplayAdminOrderTabContent($params)
    {
        /** @var EntityManagerInterface */
        $em = $this->get('doctrine.orm.entity_manager');

        $services = $em->getRepository(AgcorreiosServices::class)->findBy(['enabled' => true], ['correiosName' => 'asc']);
        $labels = $em->getRepository(AgcorreiosTracking::class)->findBy(['order' => $params['id_order']]);

        $labelsArray = [];
        $servicesArray = [];

        foreach ($labels as $label) {
            // Convert each object to an array
            $labelArray = [
                'id' => $label->getId(),
                'tracking_code' => $label->getTrackingCode(),
                'service_code' => $label->getServiceCode(),
                'date_add' => $label->getDateAdd()->format('Y-m-d H:i:s'),
                'status' => $label->getStatusAtual(),
            ];

            $labelsArray[] = $labelArray;
        }

        foreach ($services as $service) {
            // Convert each object to an array
            $serviceArray = [
                'id' => $service->getId(),
                'name' => $service->getCorreiosName(),
                'correios_code' => $service->getCorreiosCode()
            ];

            $servicesArray[] = $serviceArray;
        }

        $this->context->smarty->assign(
            [
                'services' => $servicesArray,
                'labels' => $labelsArray,
                'id_order' => $params['id_order']
            ]
        );

        return $this->display(_PS_MODULE_DIR_ . $this->name, 'views/templates/admin/content_fields.tpl');
    }

    public function hookDisplayAdminOrderTabLink($params)
    {
        return $this->display(_PS_MODULE_DIR_ . $this->name, 'views/templates/admin/tabs_fields.tpl');
    }

    public function hookDisplayTrackingButton($params)
    {
        $idOrder = $params['id_order'];

        /** @var EntityManagerInterface */
        $em = $this->get('doctrine.orm.entity_manager');

        // Fetch the order
        $order = $em->getRepository(Orders::class)->findOneBy(['id' => $idOrder]);
        if (is_null($order)) {
            return ''; // Order not found
        }

        // Check if the order has tracking information
        $trackingCount = $em->getRepository(AgcorreiosTracking::class)->count(['order' => $order]);
        if ($trackingCount === 0) {
            return ''; // No tracking information available
        }

        $this->context->smarty->assign(array(
            'id_order' => $idOrder,
        ));
        return $this->display(_PS_MODULE_DIR_ . $this->name, 'views/templates/hook/tracking_button.tpl');
    }

    //tratar se não houver alteração no rastreador ou se ele já existir
    public function hookActionObjectOrderCarrierUpdateAfter($params)
    {
        try {
            $oc = $params['object'];
            $carrier = new \Carrier($oc->id_carrier);

            if($carrier->external_module_name == 'agcorreios'){
                $trackings = \AgCorreiosTracking::getByOrder($oc->id_order);
            
                foreach ($trackings as $tracking) {
                    if ($tracking->tracking_code != $oc->tracking_number) {
                        $tracking->finished = 1;
                        $tracking->save();
                    } else {
                        return;
                    }
                }

                if ($oc->tracking_number) {
                    $tracking = new \AgCorreiosTracking();
                    $tracking->id_order = $oc->id_order;
                    $tracking->id_carrier = $carrier->id;
                    $tracking->tracking_code = $oc->tracking_number;
                    $tracking->finished = 0;
                    $tracking->save();
                }
            }
        } catch (Exception $e) {
        }
    }
}
