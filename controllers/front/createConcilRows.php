<?php

use AGTI\Correios\Application\Service\CreateConcilRowsFromFile;
use AGTI\Correios\Entity\AgcorreiosConcilBatch;
use AGTI\Correios\Entity\AgcorreiosConcilRows;
use AGTI\Correios\Entity\OrderCarrier;

class agcorreioscreateConcilRowsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        set_time_limit(0);
        ignore_user_abort(true);
        
        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agcorreios/logs/createConcilRows.log', 1);
        
        parent::initContent();

        /** @var AgClienteWorker */
        $id_worker = Tools::getValue('id_agworker');

        global $agti_worker;
        $agti_worker = new AgClienteWorker($id_worker);
        $agti_worker->save();

        $em = $this->get('doctrine.orm.entity_manager');
        begin:
        $batch = $em->getRepository(AgcorreiosConcilBatch::class)->findOneBy(['status' => 3], ['dateAdd' => 'ASC']);

        if (is_null($batch)) {
            AgClienteLogger::addLog("Nenhum batch encontrado.", 1);
            exit();
        }
        $batch->setStatus(0);
        AgClienteLogger::addLog("Processando batch {$batch->getId()}.", 1);

        $csvService = $this->get(CreateConcilRowsFromFile::class);
        $objs = $csvService->exec(_PS_MODULE_DIR_ . 'agcorreios/data/concil_batches/' . $batch->getId() . '.csv');
        foreach ($objs as $i=>$obj) {
            $obj->setBatch($batch);
            $em->persist($obj);

            if ($i % 300 == 0) {
                $agti_worker->save();
            }

        }
        $em->flush();
        AgClienteLogger::addLog("Concluindo batch.", 1);

        goto begin;
    }
}