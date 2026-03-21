<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_7_0($module)
{
    $id_tab = Tab::getIdFromClassName('AdminAgCorreios');
    if (!$id_tab) {
        $tab = new Tab();
        $tab->class_name = 'AdminAgCorreios';
        $tab->id_parent = Tab::getIdFromClassName('AdminParentShipping');
        $tab->active = 0;
        $tab->module = 'agcorreios';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Correios';
        }
        $tab->add();   
    }

    $id_tab = Tab::getIdFromClassName('AdminAgCorreiosServices');
    if ($id_tab) {
        $tab = new Tab($id_tab);
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->save();
    }else{
        $tab = new Tab();
        $tab->class_name = 'AdminAgCorreiosServices';
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->module = 'agcorreios';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Serviços';
        }
        $tab->add();    
    }

    $id_tab = Tab::getIdFromClassName('AdminAgCorreiosPrices');
    if ($id_tab) {
        $tab = new Tab($id_tab);
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->save();
    }else{
        $tab = new Tab();
        $tab->class_name = 'AdminAgCorreiosPrices';
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->module = 'agcorreios';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Preços';
        }
        $tab->add();   
    }

    $id_tab = Tab::getIdFromClassName('AdminAgCorreiosDiscounts');
    if ($id_tab) {
        $tab = new Tab($id_tab);
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->save();
    }else{
        $tab = new Tab();
        $tab->class_name = 'AdminAgCorreiosDiscounts';
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->module = 'agcorreios';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Descontos';
        }
        $tab->add();   
    }

    $id_tab = Tab::getIdFromClassName('AdminAgCorreiosInterval');
    if ($id_tab) {
        $tab = new Tab($id_tab);
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->save();
    }else{
        $tab = new Tab();
        $tab->class_name = 'AdminAgCorreiosInterval';
        $tab->id_parent = Tab::getIdFromClassName('AdminAgCorreios');
        $tab->active = 1;
        $tab->module = 'agcorreios';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Regiões';
        }
        $tab->add();    
    }

    $id_tab = Tab::getIdFromClassName('AdminAgCorreiosConfig');
    if (!$id_tab) {
        $tab = new Tab();
        $tab->class_name = 'AdminAgCorreiosConfig';
        $tab->id_parent = Tab::getIdFromClassName('AdminParentShipping');
        $tab->active = 1;
        $tab->module = 'agcorreios';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Correios';
        }
        $tab->add();  
    }
    return true;
}
