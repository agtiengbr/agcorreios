<?php
namespace AGTI\Correios\Infrastructure\API\Remote;

use AGTI\Correios\Entity\AgCorreiosApiRequest;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseService
{
    private $serializer;
    private $request;
    
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    abstract function getApiEndpoint();

    public function send($method, string $bodyData, array $querystring = [], $extraHeaders=[]): void
    {
        $startTime = microtime(true);

        $url = "https://api.correios.com.br/" . $this->getApiEndpoint();

        if ($querystring) {
            $url .= '?';
            foreach ($querystring as $i=>$q) {
                $url .= $i . '=' . $q . '&';
            }

            $url = substr($url, 0, -1);
        }
        
        if (strtoupper($method) === 'POST') {
            $payload = $bodyData;
            $extraHeaders[] = 'Content-Type:application/json';
            $methodOptions = array(
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_POST => 1
            );
        } else {
            $contentLength = null;
            $methodOptions = array(
                CURLOPT_HTTPGET => true
            );
        }
        
        $options = array(
            CURLOPT_HTTPHEADER => $extraHeaders,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        $options = ($options + $methodOptions);

        $curl = curl_init();
        curl_setopt_array($curl, $options);

        $r = curl_exec($curl);
        $info = curl_getinfo($curl);
        $ret = new AgCorreiosApiRequest;
        $ret->setResponse($r);

        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds

        $ret->setEndpoint($info['url'])
            ->setMethod($method)
            ->setBody((array)json_decode($bodyData))
            ->setHttpCode($info['http_code'])
            ->setTimeSpent($duration)
            ->setDateAdd(new \DateTime)
            ->setStack((new \Exception)->getTraceAsString());

        $ret->setTimeSpent($duration);

        $this->request = $ret;
    }

    /**
     * Get the value of serializer
     */ 
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * Set the value of serializer
     *
     * @return  self
     */ 
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Get the value of request
     */ 
    public function getRequest()
    {
        return $this->request;
    }
}