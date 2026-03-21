<?php
class AdminAgCorreiosIntervalController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap  = true;
        $this->table      = 'agcorreios_interval';
        $this->identifier = 'id_agcorreios_interval';
        $this->className  = 'AgCorreiosInterval';
        $this->noLink = true;
        $this->list_no_link = true;

        parent::__construct();

        $this->module->prepareNotifications();
        $this->fields_list = array(
            'id_agcorreios_interval' => array(
                'type' => 'int',
                'title' => 'ID',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'filter_key' => 'a!id_agcorreios_interval'
            ),
            'name' => array(
                'title' => 'Nome',
            ),
            'state' => array(
                'title' => 'UF',
                'class' => 'fixed-width-xs'
            ),
            'city' => array(
                'title' => 'Cidade',
            ),
            'zipcode_begin' => array(
                'title' => 'Início da Faixa',
            ),
            'zipcode_end' => array(
                'title' => 'Fim da Faixa',
            )
        );
    }

    
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        $this->page_header_toolbar_btn['download'] = array(
            'href' => $this->context->link->getModuleLink('agcorreios', 'createintervals', ['force' => 1]),
            'desc' => 'Atualizar Intervalos',
            'icon' => 'process-icon- icon-file',
        );


        $this->page_header_toolbar_btn['csv'] = array(
            'desc' => 'Importar regiões (CSV)',
            'icon' => 'process-icon- icon-file'
        );

        $this->page_header_toolbar_btn['configuration'] = array(
            'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->module->name,
            'desc' => 'Configurações',
            'icon' => 'process-icon- icon-cogs',
        );
    }

    public function ajaxProcessGetByZone()
    {
        $zone = AgCorreiosInterval::getByZone(Tools::getValue('id_zone'));

        $result = array(
        	'success' => 1,
        	'zone' => $zone
        );

        echo json_encode($result);
        exit();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->context->controller->addCss([
            _PS_MODULE_DIR_ . $this->module->name . '/views/css/loadingOverlay.css',
        ]);
        $this->addJs("https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js");
        $this->addJs('https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js');

        $this->addJs(_PS_MODULE_DIR_ . "agcliente/views/js/component/form/input-text.vue.js");
        $this->addJs(_PS_MODULE_DIR_ . "agcliente/views/js/component/modal.js");
        $this->addJs(_PS_MODULE_DIR_ . 'agcorreios/views/js/components/modalcsv.vue.js');

        Media::addJsDef(array(
            'urls' => [
                'importcsv'=>$this->context->link->getModuleLink('agcorreios', 'importcsv')
            ]
        ));

        $this->context->controller->addJs([
            _PS_MODULE_DIR_ . $this->module->name . '/views/js/loadingOverlay.js',
            _PS_MODULE_DIR_ . $this->module->name . '/views/js/admin/intervals_list.js'
        ]);
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);

        if (is_array($this->_list)) {
            $nb = count($this->_list);
            
            for ($i = 0; $i < $nb; $i++) {
                $this->_list[$i]['id'] = $this->_list[$i]['id_agcorreios_interval'];
            }
        }
    }
}
