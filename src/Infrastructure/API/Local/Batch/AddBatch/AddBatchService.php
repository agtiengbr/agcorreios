<?php

namespace AGTI\Correios\Infrastructure\API\Local\Batch\AddBatch;

use AGTI\Correios\Application\Service\CreateConcilRowsFromFile;
use AGTI\Correios\Entity\AgcorreiosConcilBatch;
use Doctrine\ORM\EntityManagerInterface;

class AddBatchService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function exec(AddBatchServiceArgs $args)
    {
        $batch = new AgcorreiosConcilBatch;
        $batch->setStatus(3);
        
        $this->em->persist($batch);
        $this->em->flush();

        copy($args->getTmpFileName(), _PS_MODULE_DIR_ . 'agcorreios/data/concil_batches/' . $batch->getId() . '.csv');

    }
}