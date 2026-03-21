<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos;

Use  AGTI\Correios\Infrastructure\API\Remote\BaseService;

class RastrearObjetosService extends BaseService
{
    private $args;

    public function getApiEndpoint()
    {
        $querystring = '';
        foreach ($this->args->getObjetos() as $objeto) {
            $querystring .= "codigosObjetos={$objeto}&";
        }

        $querystring .= 'resultado=T';

        return "srorastro/v1/objetos?{$querystring}";
    }

    public function exec(RastrearObjetosServiceArgs $args)
    {
        $this->args = $args;

        $r = $this->send("GET", "", [], ['Authorization: Bearer ' . $args->getToken()->getToken()]);

        if ($this->getRequest()->getHttpCode() == 200) {


            $r = $this->getSerializer()->deserialize(
                $this->getRequest()->getResponse(),
                RastrearObjetosResponseSuccess::class,
                'json'
            );
         
            $objetos = [];

            foreach ($r->getObjetos() as $objeto) {
                $eventos = [];

                if (isset($objeto['eventos'])) {
                    foreach ($objeto['eventos'] as $evento) {
                        $eventos[] = $this->getSerializer()->denormalize(
                            $evento,
                            Evento::class
                        );                    
                    }
                }

                $objeto = $this->getSerializer()->denormalize(
                    $objeto,
                    Objeto::class
                );
                $objeto->setEventos($eventos);
                $objetos[] = $objeto;
            }
            $r->setObjetos($objetos);
            return $r;
        }
        
        if ($this->getRequest()->getHttpCode() == 400) {
            $r = $this->getSerializer()->deserialize(
                $this->getRequest()->getResponse(),
                RastrearObjetosResponseError::class,
                'json'
            );
            
            return $r;
        }

        if ($this->getRequest()->getHttpCode() == 0) {
            $r = new RastrearObjetosResponseError;
            $r->setMsgs(['Erro de comunicação com os Correios. Http 0.']);

            return $r;
        }
    }
}