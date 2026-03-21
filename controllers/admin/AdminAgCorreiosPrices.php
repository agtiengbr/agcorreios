<?php
class AdminAgCorreiosPricesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap  = true;
        $this->table      = 'agcorreios_price';
        $this->identifier = 'id_agcorreios_price';
        $this->className  = 'AgCorreiosPrices';
        $this->noLink = true;
        $this->list_no_link = true;

        parent::__construct();
    
        $this->module->prepareNotifications();

        $this->_join .= '
             INNER JOIN `'._DB_PREFIX_.'agcorreios_services` s ON (s.id_agcorreios_services = a.`id_agcorreios_service`)
        ';
        $this->_select .= 's.correios_name as service,';
        
        $this->fields_list = array(
            'id_agcorreios_price' => array(
                'type' => 'int',
                'title' => 'ID',
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'service' => array(
                'title' => 'Transportadora',
                'filter_key' => 's!carrier_name'
            ),
            'state' => [
                'title' => 'Estado'
            ],
            'city' => [
                'title' => 'Cidade'
            ],
            'district' => [
                'title' => 'Bairro'
            ],
            'zipcode' => array(
                'title' => 'CEP',
            ),
            'weight' => array(
                'title' => 'Peso',
            ),
            'shipping_cost' => array(
                'type' => 'price',
                'title' => 'Preço',
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'delivery_time' => array(
                'type' => 'int',
                'title' => 'Tempo de Entrega',
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'recalculate' => [
                'title' => 'Recalcular',
                'type' => 'bool',
                'active' => 'recalculate',
                'class' => 'fixed-width-xs service-active',
                'align' => 'center'
            ]
        );

        $this->actions = ['update', 'redirect'];

        $this->bulk_actions = [
            'recalculateOn' => [
                'text' => 'Recalcular',
                'icon' => 'icon-check'
            ],
            'recalculateOff' => [
                'text' => 'Cancelar Recálculo',
                'icon' => 'icon-times'
            ]
        ];
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        $this->page_header_toolbar_btn['csv'] = array(
            'href' => $this->context->link->getAdminLink('AdminAgCorreiosPrices', true, [], ['action' => 'generateCsv']),
            'desc' => 'Gerar CSV',
            'icon' => 'process-icon- icon-file',
        );

        $this->page_header_toolbar_btn['configuration'] = array(
            'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->module->name,
            'desc' => 'Configurações',
            'icon' => 'process-icon- icon-cogs',
        );
    }

    public function initContent()
    {
        if (Tools::getValue('action') === 'generateCsv') {
            $this->generateCsv();
        } elseif (Tools::getIsSet('recalculate' . $this->table)) {
            $obj = $this->loadObject();
            $obj->recalculate = !$obj->recalculate;
            $obj->update();

            Tools::redirectAdmin(self::$currentIndex .'&token=' . $this->token . '&conf=4');
            exit();
        }

        parent::initContent();
    }

    public function generateCsv()
    {
        AgCorreiosPrices::generateCsvForSkyhub();
    }


    /******************************* AÇÕES INDIVIDUAIS ***********************************/
    public function displayUpdateLink(
        $token,
        $id,
        $name
    ) {
        $link = $this->context->link->getModuleLink($this->module->name, 'CalcPrice', ['id_agcorreios_price' => $id]);

        $this->context->smarty->assign(['url' => $link]);
        $tpl = $this->createTemplate('helpers/list/update.tpl');
        return $tpl->fetch();
    }

    public function displayRedirectLink(
        $token,
        $id,
        $name
    ) {
        $link = $this->context->link->getModuleLink($this->module->name, 'CalcPrice', ['id_agcorreios_price' => $id, 'debug' => 1]);

        $this->context->smarty->assign(['url' => $link]);
        $tpl = $this->createTemplate('helpers/list/redirect.tpl');
        return $tpl->fetch();
    }

    /******************************* AÇÕES EM MASSA ***********************************/

    public function processBulkForceRecalculate()
    {
        $return = true;

        if (is_array($this->boxes) && !empty($this->boxes)) {
            foreach ($this->boxes as $id) {
                try {
                    $object = new AgCorreiosPrices($id);

                    if (!Validate::isLoadedObject($object)) {
                        throw new Exception(sprintf(
                            "Erro carregando objeto $id do banco de dados."
                        ));
                    }

                    $object->force_recalculate = 1;

                    if (!$object->update()) {
                        throw new Exception(sprintf(
                            "Erro atualizando objeto $id no banco de dados."
                        ));   
                    }
                } catch (Exception $e) {
                    $this->module->errors[] = $e->getMessage();
                    $return = false;
                }
            }
        }

        if (count($this->module->errors) == 0) {
            $this->module->confirmations[] = "Ação realizada com sucesso!";
        }
        
        $this->module->saveNotifications();
        Tools::redirectAdmin(self::$currentIndex . '&token=' . $this->token);

        exit();
    }

    public function processBulkCancelRecalculate()
    {
        $return = true;

        if (is_array($this->boxes) && !empty($this->boxes)) {
            foreach ($this->boxes as $id) {
                try {
                    $object = new AgCorreiosPrices($id);

                    if (!Validate::isLoadedObject($object)) {
                        throw new Exception(sprintf(
                            "Erro carregando objeto $id do banco de dados."
                        ));
                    }

                    $object->force_recalculate = 0;

                    if (!$object->update()) {
                        throw new Exception(sprintf(
                            "Erro atualizando objeto $id no banco de dados."
                        ));   
                    }
                } catch (Exception $e) {
                    $this->module->errors[] = $e->getMessage();
                    $return = false;
                }
            }
        }

        if (count($this->module->errors) == 0) {
            $this->module->confirmations[] = "Ação realizada com sucesso!";
        }
        
        $this->module->saveNotifications();
        Tools::redirectAdmin(self::$currentIndex . '&token=' . $this->token);

        exit();
    }


        /******************* ações em massa ************************/
        protected function processBulkRecalculateOn()
        {
            return $this->processBulkRecalculate(1);
        }
    
        protected function processBulkRecalculateOff()
        {
            return $this->processBulkRecalculate(0);
        }
    
        protected function processBulkRecalculate($status)
        {
            if (is_array($this->boxes) && !empty($this->boxes)) {
                foreach ($this->boxes as $id) {
                    /** @var ObjectModel $object */
                    $object = new $this->className((int)$id);
                    $object->recalculate = (int)$status;
                    if (!$object->update()) {
                        $msg_error = Db::getInstance()->getMsgError();
                        $this->module->errors[] = "Erro atualizando o preço {$id} - {$msg_error}";
                    } else {
                        $this->module->confirmations[] = "Preço {$id} atualizado com sucesso!";
                    }
                }
            }
        }
}
