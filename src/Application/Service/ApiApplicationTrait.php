<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Application\Utils\ValidateApiResponse;
use AGTI\Correios\Entity\AgCorreiosApiRequest;
use Doctrine\ORM\EntityManagerInterface;

trait ApiApplicationTrait
{
    protected function postApiRequest(AgCorreiosApiRequest $r, EntityManagerInterface $manager)
    {
        $manager->persist($r);
        $manager->flush();

        (new ValidateApiResponse)->validate($r);
    }
}