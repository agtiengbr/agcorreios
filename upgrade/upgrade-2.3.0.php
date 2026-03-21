<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_2_3_0($module)
{
    /***********************  cria as tabelas no banco **********************/
    $object_models = [
        'AgCorreiosIntervalTmp'
    ];
    
    foreach ($object_models as $class) {
        $modelInstance = new $class;

        if (method_exists($class, 'createDatabase')) {
            $modelInstance->createDatabase();
        }

        if (method_exists($class, 'createMissingColumns')) {
            $modelInstance->createMissingColumns();
        }

        if (method_exists($class, 'createIndexes')) {
            $modelInstance->createIndexes();
        }

        if (method_exists($class, 'createDefaultData')) {
            $class::createDefaultData();
        }
    }

    try {
        Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'agcorreios_price');
        $error = Db::getInstance()->getMsgError();

        if ($error) {
            throw new Exception($error);
        }
    } catch (Exception $e){
        Logger::addLog("Erro atualizando o módulo agcorreios para a versão 2.3.0 - " . $e->getMessage(), 3);
        return false;
    }

    try {
        Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'agcorreios_interval');
        $error = Db::getInstance()->getMsgError();

        if ($error) {
            throw new Exception($error);
        }
    } catch (Exception $e){
        Logger::addLog("Erro atualizando o módulo agcorreios para a versão 2.3.0 - " . $e->getMessage(), 3);
        return false;
    }
    
    $url = Context::getContext()->link->getModuleLink('agcorreios', 'createintervals', ['force' => 1]);
    AgCommunicator::doCurlRequestAsync($url);

    return true;
}