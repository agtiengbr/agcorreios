<?php

use AGTI\Correios\Application\Service\TokenRetriever;
use AGTI\Correios\Application\Service\TrackingObjects;
use AGTI\Correios\ValueObject\Configuration as CorreiosConfiguration;

/** Refresh a single tracking page without running the order-status worker. */
class AgCorreiosTrackingRefresh
{
    public function exec($module, $trackingCode)
    {
        $services = [
            $module->get(\AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem\AutenticaCartaoDePostagemService::class),
            $module->get(\AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\RastrearObjetosService::class),
        ];
        $timeouts = [];
        foreach ($services as $index => $service) {
            $timeouts[$index] = $service->getRequestTimeout();
            $service->setRequestTimeout(15);
        }
        try {
            return $this->refresh($module, $trackingCode);
        } finally {
            foreach ($services as $index => $service) {
                $service->setRequestTimeout($timeouts[$index]);
            }
        }
    }

    private function refresh($module, $trackingCode)
    {
        $trackingCode = strtoupper(trim((string) $trackingCode));
        if (!preg_match('/^[A-Z]{2}[0-9]{9}[A-Z]{2}$/D', $trackingCode)) {
            throw new RuntimeException('Código de rastreio inválido.');
        }

        $config = $module->get(CorreiosConfiguration::class);
        $token = $module->get(TokenRetriever::class)->exec(
            $config->getUsername(), $config->getPassword(), $config->getCartaoPostagem()
        );
        $response = $module->get(TrackingObjects::class)->exec(
            [(object) ['tracking_code' => $trackingCode]], $token
        );
        $matched = null;
        foreach ((array) $response->getObjetos() as $object) {
            if ($object->getCodObjeto() === $trackingCode) {
                $matched = $object;
                break;
            }
        }
        // A 200 response may still contain SRO-009 or another per-object error.
        if (!$matched || $matched->getMensagem() || !$matched->getEventos()) {
            throw new RuntimeException('Os Correios não retornaram eventos para o objeto.');
        }

        $events = [];
        foreach ($matched->getEventos() as $event) {
            if (!$event->getDtHrCriado() instanceof DateTimeInterface || !$event->getDescricao()) {
                throw new RuntimeException('Evento de rastreio incompleto.');
            }
            $events[] = [
                'code' => $event->getCodigo(),
                'type' => $event->getTipo(),
                'desc' => $event->getDescricao(),
                'created' => $event->getDtHrCriado()->format('Y-m-d H:i:s'),
                '_unity' => $event->getUnidade(),
            ];
        }
        usort($events, function ($a, $b) { return strcmp($b['created'], $a['created']); });
        $this->saveKnownTrackingEvents($trackingCode, $events);
        foreach ($events as &$event) {
            unset($event['_unity']);
        }
        unset($event);
        return AgCorreiosTracking::normalizeTrackingEventsForDisplay($events);
    }

    private function saveKnownTrackingEvents($trackingCode, array $events)
    {
        // Include completed trackings; never create an order/label from a public lookup.
        $query = new DbQuery();
        $query->select('id_agcorreios_tracking')->from('agcorreios_tracking')
            ->where('tracking_code = "' . pSQL($trackingCode) . '"');
        $trackings = Db::getInstance()->executeS($query);
        if (!is_array($trackings)) {
            throw new RuntimeException('Não foi possível ler os rastreamentos.');
        }
        foreach ($trackings as $tracking) {
            foreach ($events as $event) {
                $query = new DbQuery();
                $query->select('id_agcorreios_tracking_events')->from('agcorreios_tracking_events')
                    ->where('id_agcorreios_tracking = ' . (int) $tracking['id_agcorreios_tracking'])
                    ->where('code = "' . pSQL($event['code']) . '"')
                    ->where('type = "' . pSQL($event['type']) . '"')
                    ->where('created = "' . pSQL($event['created']) . '"')
                    ->where('`desc` = "' . pSQL($event['desc']) . '"');
                if (Db::getInstance()->getValue($query)) {
                    continue;
                }
                $unityId = null;
                $remoteUnity = $event['_unity'];
                if ($remoteUnity && $remoteUnity->getEndereco()) {
                    $unity = AgCorreiosUnity::get($remoteUnity);
                    if (!$unity->id) {
                        $unity->cod_sro = $remoteUnity->getCodSro();
                        $unity->type = $remoteUnity->getTipo();
                        $unity->city = $remoteUnity->getEndereco()->getCidade();
                        $unity->state = $remoteUnity->getEndereco()->getUf();
                        if (!$unity->save()) {
                            throw new RuntimeException('Não foi possível gravar a unidade.');
                        }
                    }
                    $unityId = $unity->id;
                }
                $stored = new AgCorreiosTrackingEvents();
                $stored->id_agcorreios_unity = $unityId;
                $stored->id_agcorreios_tracking = (int) $tracking['id_agcorreios_tracking'];
                $stored->code = $event['code'];
                $stored->type = $event['type'];
                $stored->created = $event['created'];
                $stored->desc = $event['desc'];
                if (!$stored->save()) {
                    throw new RuntimeException('Não foi possível gravar o evento.');
                }
            }
        }
    }
}
