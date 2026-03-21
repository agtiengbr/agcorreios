<?php

namespace AGTI\Correios\Application\Utils;

use AGTI\Correios\Application\Exception\UnauthorizedException;
use AGTI\Correios\Application\Exception\ApiException;
use AGTI\Correios\Application\Exception\BadRequestException;
use AGTI\Correios\Entity\AgCorreiosApiRequest;

class ValidateApiResponse
{
    public function validate(AgCorreiosApiRequest $r)
    {
        if ($r->getHttpCode() < 200 || $r->getHttpCode() >= 300) {
            if ($r->getHttpCode() == 400) {
                $e = new BadRequestException("A API retornou código HTTP " . $r->getHttpCode());
                $e->setApiRequest($r);
                throw $e;
            }

            if ($r->getHttpCode() == 401) {
                $e = new UnauthorizedException("A API retornou código HTTP " . $r->getHttpCode());
                $e->setApiRequest($r);
                throw $e;
            }

            $e = new ApiException("A API retornou código HTTP " . $r->getHttpCode());
            $e->setApiRequest($r);
            throw $e;
        }
    }
}