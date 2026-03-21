<?php

use AGTI\Correios\Entity\AgcorreiosConcilBatch;
use AGTI\Correios\Entity\AgcorreiosConcilRows;
use AGTI\Correios\Entity\OrderCarrier;

class agcorreiosconcilPricesModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        set_time_limit(0);
        ignore_user_abort(true);
        
        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agcorreios/logs/concilPrices.log', 1);
        
        parent::initContent();

        /** @var AgClienteWorker */
        $id_worker = Tools::getValue('id_agworker');

        global $agti_worker;
        $agti_worker = new AgClienteWorker($id_worker);
        $agti_worker->save();

        $em = $this->get('doctrine.orm.entity_manager');
        begin:
        //batch em progresso
        $batch = $em->getRepository(AgcorreiosConcilBatch::class)->findOneBy(['status' => 1]);

        if (is_null($batch)) {
            //batch não iniciado
            $batch = $em->getRepository(AgcorreiosConcilBatch::class)->findOneBy(['status' => 0], ['dateAdd' => 'ASC']);
            if (!is_null($batch)) {
                $batch->setDateAnalysisBegin(new \DateTime);
            }
        }

        if (is_null($batch)) {
            AgClienteLogger::addLog("Nenhum batch encontrado.", 1);
            exit();
        }
        $batch->setStatus(1);
        $em->flush();

        while(1) {
            AgClienteLogger::addLog("Processando batch {$batch->getId()}.", 1);
            $rows = $em->getRepository(AgcorreiosConcilRows::class)->findBy(['status' => 0, 'batch' => $batch], [], [100]);
            if (count($rows) == 0) {
                AgClienteLogger::addLog("Nenhuma linha a processar..", 1);
                break;
            }

            $batch->setTotalRows($batch->getTotalRows() + count($rows));
            
            foreach ($rows as $row) {
                AgClienteLogger::addLog("Processando linhas.", 1);
                $oc = $em->getRepository(OrderCarrier::class)->findOneBy(['trackingNumber' => $row->getTrackingNumber()]);
                $row->setOrderCarrier($oc);

                if (is_null($oc)) {
                    $batch->setRowsNotFound($batch->getRowsNotFound() + 1);
                } elseif ($oc->getShippingCost() == $row->getCost()) {
                    $batch->setRowsOk($batch->getRowsOk() + 1);
                } else {
                    $batch->setRowsPriceError($batch->getRowsPriceError() + 1);
                }

                $row->setStatus(1);
                $row->setDateAnalysis(new \DateTime);
            }

            $em->flush();
            $agti_worker->save();
        }

        AgClienteLogger::addLog("Concluindo batch.", 1);
        $batch->setStatus(2);
        $batch->setDateAnalysisEnd(new \DateTime);
        $em->flush();

        goto begin;
    }
}